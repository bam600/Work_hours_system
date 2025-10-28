<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // StaffSeeder と IsAdminUserSeeder を呼び出す
        $this->call([
            StaffSeeder::class,
        ]);
    }
}
