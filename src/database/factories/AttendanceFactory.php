<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Staff;
use App\Models\Attendance;

class AttendanceFactory extends Factory
{

    protected $model = Attendance::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
{
    $start = $this->faker->dateTimeBetween('2025-01-01 08:00:00', '2025-12-31 10:00:00');
    $end = Carbon::parse($start)->copy()->addHours(rand(6, 9));
    $actual = Carbon::parse($start)->diff($end)->format('%H:%I:%S');

    return [
        'staff_id' => Staff::factory(), // ← 関連付けがあるならこっち！
        'clock_in' => $start,
        'clock_out' => $end,
        'actual_work_time' => $actual,
        'status' => $this->faker->randomElement(['出勤', '退勤', '休憩']),
    ];
}

}
