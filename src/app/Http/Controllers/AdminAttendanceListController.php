<?php
// 管理者用勤怠一覧
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Staff;
use Carbon\Carbon;

class AdminAttendanceListController extends Controller
{
    public function adminrequestlist(Request $request)
    {
        /**当日を取得 */
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $attendances = Attendance::with(['staff','breaks'])
            ->whereNotNull('clock_in')
            ->orderBy('clock_in', 'asc')
            ->get()
            ->groupBy(function ($record) {
                return Carbon::parse($record->clock_in)->format('Y-m-d');
            });

    $dailySummaries = [];

    foreach ($attendances as $groupedDate => $records) {
        $dailySummaries[$groupedDate] = [];

        foreach ($records as $record) {
            // 休憩の合計時間（分）を計算
            $totalBreakMinutes = $record->breaks->sum(function ($break) {
                if ($break->break_start && $break->break_end) {
                    return Carbon::parse($break->break_end)->diffInMinutes(Carbon::parse($break->break_start));
            }
                    return 0;
            });
                // 整形済み休憩時間
                $breakTimeFormatted = sprintf('%02d:%02d', floor($totalBreakMinutes / 60), $totalBreakMinutes % 60);

                //勤務時間を取得するための計算
                $workMinutes = 0;
                $clockInFormatted = null;
                $clockOutFormatted = null;
                $workTimeFormatted = null;

            if ($record->clock_in && $record->clock_out) {
                $in = Carbon::parse($record->clock_in);
                $out = Carbon::parse($record->clock_out);
                $clockInFormatted = $in->format('H:i');
                $clockOutFormatted = $out->format('H:i');

                $workMinutes = $out->diffInMinutes($in) - $totalBreakMinutes;
                $workTimeFormatted = sprintf('%02d:%02d', floor($workMinutes / 60), $workMinutes % 60);
            }


            $dailySummaries[$groupedDate][] = [
                'staff_name'    => $record->staff->user_name,
                'staff_id'      => $record->staff_id, // ← これを追加！
                'clock_in'      => $clockInFormatted ?? '未出勤',
                'clock_out'     => $clockOutFormatted ?? '未退勤',
                'break_time'    => $breakTimeFormatted,
                'work_time'     => $workTimeFormatted ?? '00:00',
                'id'            => $record->id,
            ];
        }
}
    return view('adminrequestlist', [
        'date' => $date,
        'dailyRecords' => $dailySummaries[$date->format('Y-m-d')] ?? [],
    ]);

    }
}