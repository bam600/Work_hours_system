<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// 商品conditionテーブル用seeder
class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'code' => 'Good',
            'label' => '良好',
        ];
    DB::table('conditions')->insert($param);

        $param = [
            'code' => 'Excellent',
            'label' => '目立った傷や汚れなし',
        ];
    DB::table('conditions')->insert($param);

        $param = [
            'code' => 'Fair',
            'label' => 'やや傷や汚れあり',
        ];
    DB::table('conditions')->insert($param);

        $param = [
            'code' => 'Poor',
            'label' => '状態が悪い',
        ];
    DB::table('conditions')->insert($param);

    }
}
