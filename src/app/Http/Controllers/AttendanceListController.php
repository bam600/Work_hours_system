<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakModel;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceListController extends Controller
{
    public function create(Request $request)
    {   

        $staffId = $request->input('staff_id', auth()->id());

        /**当月を取得 */
        $month = $request->input('month', Carbon::today()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();;

        /**月初・月末の定義を先に！*/
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        /**該当月の日数を取得 */
        $daysInMonth = $date->daysInMonth;
        /**日ごとのデータを整形*/
        $dailyRecords = [];
        /** 曜日の日本語設定 */
        Carbon::setLocale('ja');
        /**合計時間の初期化 */
        $totalMinutes = 0;

        /**ログイン中スタッフauth()->id()の
         * 該当月に出勤clock_inしたレコードを取得
         * */
        $targetDays = Attendance::with('breaks') // ← これを追加！
            ->where('staff_id', auth()->id())
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->get();

        /**
         * その月の1日から最終日まで順番にループ
         * $day = 1：スタートは1日
         * $day <= $daysInMonth：月末まで繰り返す（例えば30日なら30まで）
         * $day++：1日ずつ進めていく
         */
        for ($day = 1; $day <= $daysInMonth; $day++) {
            /**$currentDateに日付を複製day($day)$day=5などで日付*変更*/
            $currentDate = $date->copy()->day($day);
            /**日付・曜日以外空欄スタート(一覧初期化) */
            $record = [
            'date' => $currentDate->format('m/d'),
            'weekday' => $currentDate->isoFormat('dd'),
            'clock_in' => '',
            'clock_out' => '',
            'work_time' => '',
            'break_time' => '',
            'staff_id' => $targetDay?->staff_id ?? $staffId, // ← ここを追加！
        ];

        /**その日に出勤したレコードを
         * $targetDaysの中から1件だけ探す
         * $targetDaysはその月に出勤した全レコードのコレクション
         * ->first(function (...) {...})最初の条件にあう1件を探す
         */
        $targetDay = $targetDays
        /**外の$currentDateを中で使用できるようにしている */
        ->first(function ($item) use ($currentDate) {

            /**出勤記録の日時とcurrentDateが同日か判定 */
            return Carbon::parse($item->clock_in)->isSameDay($currentDate);
    });

        if ($targetDay && $targetDay->clock_out) {
            /**出勤時間を日付オブジェクトに変換して$inに格納*/
            $in = Carbon::parse($targetDay->clock_in);
            /**退勤時間を日付オブジェクトに変換して$inに格納*/
            $out = Carbon::parse($targetDay->clock_out);

            $record['clock_in'] = $in->format('H:i');
            $record['clock_out'] = $out->format('H:i');
            $workMinutes = $out->diffInMinutes($in);
            $record['work_time'] = sprintf('%02d:%02d', floor($workMinutes / 60), $workMinutes % 60);

            
            /**退勤時間-出勤時間=勤怠時間を $totalMinutesに格納*/
            $totalMinutes += $workMinutes;
            }


        $breakMinutes = 0;
        if ($targetDay) {
            foreach ($targetDay->breaks as $break) {
                if ($break->start_time && $break->end_time) {
                    $start = Carbon::parse($break->start_time);
                    $end = Carbon::parse($break->end_time);
                    $breakMinutes += $end->diffInMinutes($start);
                    
                }
            }
        }

        if ($breakMinutes > 0) {
            $record['break_time'] = sprintf('%02d:%02d', floor($breakMinutes / 60), $breakMinutes % 60);
        } else {
            $record['break_time'] = sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60);
        }
            $record['id'] = $targetDay?->id;
            $dailyRecords[] = $record;
} 
    
        /**データを渡して勤怠一覧に戻る。*/
        return view('attendancelist', compact('dailyRecords','date'));
    }
}
    
