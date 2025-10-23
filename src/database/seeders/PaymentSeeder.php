<?php
/**支払い管理テーブル用seeder */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            ['id' => 1, 'name' => 'カード払い', 'code' => 'credit'],
            ['id' => 2, 'name' => 'コンビニ払い', 'code' => 'conveni'],
        ]);
    }
}
