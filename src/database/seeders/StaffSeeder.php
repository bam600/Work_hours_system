<?php
// Staffテーブルのダミー情報
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // 例：20件作成（重複制約に合わせて件数は調整）
        Staff::factory()->count(45)->create();
    }
}

