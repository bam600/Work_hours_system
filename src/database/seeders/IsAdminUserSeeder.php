<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\IsAdminUser;

class IsAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Staffが存在するか確認
        $staffList = Staff::all();

        if ($staffList->count() < 5) {
            echo "Staffが5件未満のため、IsAdminUserSeederはスキップされました。\n";
            return;
        }

        // ランダムに5件抽出
        $adminStaffs = $staffList->random(5);

        foreach ($adminStaffs as $staff) {
            IsAdminUser::factory()->create([
                'staff_id' => $staff->id,
                'employee_number' => $staff->employee_number,
                'is_admin' => true,
            ]);
        }
    }
}
