<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakModel;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceListController extends Controller
{
    public function create()
    {


        /**当月を取得 */
        $month = Carbon::today()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();


        /**
         * 集計対象月の開始から終了を作成
         * Carbon::parse($month)は
         * 日付の文字列を日付オブジェクトに変換して
         * startOfMonth()は1日の00:00:00(開始時間)
         * */
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        /** endOfMonth()は月末は23:59:59)*/
        $endOfMonth = Carbon::parse($month)->endOfMonth();
        

        /**ログイン中スタッフ
         * auth()->id()の
         * 該当月に出勤clock_inしたレコードを取得
         * */
        $targetDays = Attendance::where('staff_id', auth()->id())
            /**whereBetween('clock_in', [開始, 終了]) は、clock_in が
            * その月の範囲に入るものだけに絞る条件*/
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->get();

        /**日ごとのデータを整形*/
        $dailyRecords = [];
        /** 曜日の日本語設定 */
        Carbon::setLocale('ja');
        /**合計時間の初期化 */
        $totalMinutes = 0;

        /**1レコード（1日分など）ずつ取り出して
         * 出勤時刻$in・退勤時刻$outをCarbonに変換。*/
        foreach ($targetDays as $targetDay) {
            /**出勤時間かつ退勤時間が入力されていたら下記の処理*/
            if ($targetDay->clock_in && $targetDay->clock_out) {
                /**出勤時間を日付オブジェクトに変換して$inに格納*/
                $in = Carbon::parse($targetDay->clock_in);
                /**退勤時間を日付オブジェクトに変換して$inに格納*/
                $out = Carbon::parse($targetDay->clock_out);

                $workMinutes = $out->diffInMinutes($in);
                /**退勤時間-出勤時間=勤怠時間を $totalMinutesに格納*/
                $totalMinutes += $workMinutes;

                /**休憩時間の計算 */
                $breakMinutes = 0;
                foreach($targetDay->breaks as $break){
                    if($break->start_time && $break->end_time);
                        $start = Carbon::parse($break->start_time);
                        $end = Carbon::parse($break->end_time);
                        $breakMinutes += $end->diffInMinutes($start);
                }

                $dailyRecords[] = [
                /**〇月〇日として変換してdateに格納 */
                'date'=>$in->format('m/d'),
                /**曜日に変換してweekdayに格納 */
                'weekday'=>$in->isoFormat('dd'),
                 /**勤務時間を〇：〇〇形式に変換してclock_inに格納 */
                'clock_in'=>$in->format('H:i'),
                /**退勤時間を〇：〇〇形式に変換してclock_outに格納 */
                'clock_out'=>$out->format('H:i'),
                /**合計勤務時間を〇：〇〇(時間：分)に変換する
                * (sprintf('%02d:%02d', ...) は、ゼロ埋めして
                * "08:30" みたいにきれいに表示するための書き方！)
                */
                'work_time' => sprintf('%02d:%02d', floor($workMinutes / 60), $workMinutes % 60),
                'break_time' => sprintf('%02d:%02d', floor($breakMinutes / 60), $breakMinutes % 60),
                ];
            }
        }

                // 勤務時間（時間）
                $totalHours = floor($totalMinutes / 60);
                // 勤務時間（分）
                $remainingMinutes = $totalMinutes % 60; 

        /**データを渡して勤怠一覧に戻る。*/
        return view('attendancelist', compact('dailyRecords','date'));
    }
}
    
