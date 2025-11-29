<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Staff;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition()
    {
        return [
            'user_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_admin' => $this->faker->boolean(),
            'password' => bcrypt('00000000'),
        ];
    }
}