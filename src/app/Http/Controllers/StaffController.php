<?php
// PG12 スタッフ別勤怠一覧(管理者)
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakModel;
use App\Models\Staff;
use App\Models\Attendance;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffController extends Controller
{
    public function show(Request $request, $id, $month = null)
    {
        /**当月を取得 */
        $month = $month ?? Carbon::today()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

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

        $staffId = $id; // ← これを追加！


        /**ログイン中スタッフauth()->id()の
         * 該当月に出勤clock_inしたレコードを取得　スタッフ名も取得
         * */
         $targetDays = Attendance::with(['staff', 'breaks']) // staffリレーションを読み込む
            ->where('staff_id', $id)
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->get();

        $staff = Staff::findOrFail($id);

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

        $workMinutes = 0; // ← ここを追加

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
            $record['break_time'] = '';
        }

        // 実働時間 = 勤務時間 - 休憩時間
        $actualMinutes = max(0, $workMinutes - $breakMinutes);
        $record['actual_work_time'] = sprintf('%02d:%02d', floor($actualMinutes / 60), $actualMinutes % 60);
        
        $record['id'] = $targetDay?->id;
        $dailyRecords[] = $record;
} 
    
        /**データを渡して勤怠一覧に戻る。*/
        $firstRecord = $targetDays->first();
        return view('staff', compact('dailyRecords','date','id','firstRecord','staff'));

    }

    // CSV出力
public function exportCsv(Request $request, $id)
{
    $month = $request->input('month', now()->format('Y-m'));
    $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

    $attendances = Attendance::where('staff_id', $id)
        ->whereBetween('clock_in', [$start, $end])
        ->with('breaks')
        ->get();

    $response = new StreamedResponse(function() use ($attendances) {
        if (ob_get_level() > 0) {
            ob_end_clean(); // 出力バッファをクリア
        }

        $handle = fopen('php://output', 'w');
        stream_filter_prepend($handle, 'convert.iconv.UTF-8/SJIS-win');

        // ヘッダー行
        fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

        foreach ($attendances as $attendance) {
            // 勤務時間計算
            $in = $attendance->clock_in ? Carbon::parse($attendance->clock_in) : null;
            $out = $attendance->clock_out ? Carbon::parse($attendance->clock_out) : null;
            $workMinutes = ($in && $out) ? $out->diffInMinutes($in) : 0;

            // 休憩時間計算
            $breakMinutes = 0;
            foreach ($attendance->breaks as $break) {
                if ($break->start_time && $break->end_time) {
                    $start = Carbon::parse($break->start_time);
                    $end = Carbon::parse($break->end_time);
                    $breakMinutes += $end->diffInMinutes($start);
                }
            }

            $actualMinutes = max(0, $workMinutes - $breakMinutes);

            $breakTime = sprintf('%02d:%02d', floor($breakMinutes / 60), $breakMinutes % 60);
            $actualWorkTime = sprintf('%02d:%02d', floor($actualMinutes / 60), $actualMinutes % 60);

            fputcsv($handle, [
                $in?->format('Y-m-d') ?? '',
                $in?->format('H:i') ?? '',
                $out?->format('H:i') ?? '',
                $breakTime,
                $actualWorkTime,
            ]);
        }

        fclose($handle);
    });

    $filename = 'staff_'.$id.'_attendance_'.$month.'.csv';
    $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
    $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

    return $response;
}
}
    
