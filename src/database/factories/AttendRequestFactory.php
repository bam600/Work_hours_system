<?php

namespace Database\Factories;

use App\Models\AttendRequest;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class AttendRequestFactory extends Factory
{
    protected $model = AttendRequest::class;

    public function definition()
    {
        // Attendanceを先に作成（statusはランダムでcheckout含む）
        $attendance = Attendance::factory()->create([
            'status' => $this->faker->randomElement(['checkin', 'checkout', 'absent']),
        ]);

        // attendance.statusがcheckoutのときだけstatusをランダムに設定
        $requestStatus = $attendance->status === 'checkout'
            ? Arr::random(['pending', 'approved', 'rejected']) // nullを除外！
            : 'pending'; // checkout以外は 'pending' に固定

        return [
            'attendance_id' => $attendance->id,
            'staff_id' => Staff::factory(),
            'status' => $requestStatus,
            'approved_by' => $requestStatus === 'approved'
                ? optional(Staff::inRandomOrder()->first())->id
                : null,
            'approved_at' => $requestStatus === 'approved'
                ? Carbon::now()->subDays(rand(0, 7))
                : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
