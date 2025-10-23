<?php

//出品商品カテゴリー管理テーブルのダミーデータ
namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryExhibitionSeeder extends Seeder
{
    public function run()
    {
        $pairs = [
            ['exhibition_id' => 3, 'category_id' => 1],
            ['exhibition_id' => 4, 'category_id' => 5],
            ['exhibition_id' => 5, 'category_id' => 2],
            ['exhibition_id' => 6, 'category_id' => 10],
            ['exhibition_id' => 7, 'category_id' => 1],
            ['exhibition_id' => 7, 'category_id' => 5],
            ['exhibition_id' => 8, 'category_id' => 1],
            ['exhibition_id' => 9, 'category_id' => 2],
            ['exhibition_id' => 10, 'category_id' => 1],
            ['exhibition_id' => 10, 'category_id' => 4],
            ['exhibition_id' => 10, 'category_id' => 11],
            ['exhibition_id' => 11, 'category_id' => 10],
            ['exhibition_id' => 12, 'category_id' => 10],
            ['exhibition_id' => 20, 'category_id' => 4],
            ['exhibition_id' => 20, 'category_id' => 6],
            //必要なペアを追加（最大25件まで）
        ];

        DB::table('category_exhibition')->insert($pairs);
    }
}
