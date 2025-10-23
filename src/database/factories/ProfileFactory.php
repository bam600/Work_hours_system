<?php
// プロフィールテーブル用ダミーデータ
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProfileFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'postal_code' => $this->faker->numerify('###-####'),
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress(),
        ];
    }
}

