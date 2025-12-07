<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Staff;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    $staffs = Staff::factory()->count(30)->create();

    foreach ($staffs as $staff) {
    for ($i = 0; $i < 120; $i++) {
        $date = now()->copy()->subDays($i)->startOfDay(); // 日付を固定（時刻なし）

        // すでにそのスタッフ・日付の勤怠があるかチェック
        $exists = Attendance::where('staff_id', $staff->id)
            ->whereDate('clock_in', $date)
            ->exists();

        if (!$exists) {
            $clockIn = $date->copy()->setTime(9, 0);
            $clockOut = $date->copy()->setTime(18, 0);
            $workMinutes = $clockOut->diffInMinutes($clockIn);
            $workTimeFormatted = sprintf('%02d:%02d', floor($workMinutes / 60), $workMinutes % 60);

            Attendance::create([
                'staff_id' => $staff->id,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'actual_work_time' => $workTimeFormatted,
                'status' => 'checkout',
            ]);
        }
    }
}
}
}