<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IsAdminUser;

class IsAdminUserFactory extends Factory
{
    protected $model = IsAdminUser::class;

    public function definition(): array
    {
        return [
            'staff_id' => null,
            'employee_number' => null,
            'is_admin' => true,
        ];
    }
}
