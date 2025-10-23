<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
public function run()
{
    // Factoryによる基本データ生成
    \App\Models\User::factory()->count(15)->create();
    \App\Models\Profile::factory()->count(15)->create();

    // Seederの実行順序（依存関係を考慮）
    $this->call([
        CategorySeeder::class,              // ① カテゴリ（中間テーブルより先）
        ConditionSeeder::class,            // ② 商品状態（Exhibitionに依存するなら先）
        PaymentSeeder::class,              // ③ 支払い方法（Exhibitionに依存するなら先）
        ExhibitionSeeder::class,           // ④ 出品情報（カテゴリ・ユーザーに依存）
        ProfileSeeder::class,              // ⑥ プロフィール（Userに依存）
        CategoryExhibitionSeeder::class,   // ⑤ 中間テーブル（ExhibitionとCategoryが必要）
    ]);
}

    }

