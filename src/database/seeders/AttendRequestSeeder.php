<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendRequest;

class AttendRequestSeeder extends Seeder
{
    public function run(): void
    {
        AttendRequest::factory()->count(50)->create();
    }
}
