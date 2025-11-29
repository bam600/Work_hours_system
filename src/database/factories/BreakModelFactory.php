<?php
// Breakモデルのファクトリ
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Attendance;
use App\Models\BreakModel;

class BreakModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 2025年の1月1日〜12月31日の間でランダムな開始時間
        $start = $this->faker->dateTimeBetween('2025-01-01 00:00:00', '2025-12-31 23:59:59');
        $end = Carbon::parse($start)->copy()->addMinutes(rand(15, 60));

            return [
            'attendance_id' => Attendance::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'actual_break_time' => Carbon::parse($end)->diff(Carbon::parse($start))->format('%H:%I:%S'),
        ];
    }
}
