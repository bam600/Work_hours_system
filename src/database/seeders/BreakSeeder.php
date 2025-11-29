<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\BreakModel;
use Carbon\Carbon;

class BreakSeeder extends Seeder
{
    public function run()
    {
        // 勤怠がちゃんと入ってるものだけ取得
        $attendances = Attendance::whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->inRandomOrder()
            ->take(1000)
            ->get();

        foreach ($attendances as $attendance) {
            $start = Carbon::parse($attendance->clock_in)->copy()->addHours(rand(1, 5))->addMinutes(rand(0, 30));
            $end = $start->copy()->addMinutes(rand(15, 60));

            BreakModel::create([
                'attendance_id' => $attendance->id,
                'start_time' => $start,
                'end_time' => $end,
                'actual_break_time' => $end->diff($start)->format('%H:%I:%S'),
            ]);
        }
    }
}