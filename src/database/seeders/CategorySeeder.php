<?php
// 商品カテゴリー用
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $param = [
                'name' => 'ファッション',
                'slug' => 'fashion',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => '家電',
                'slug' => 'electronics',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'インテリア',
                'slug' => 'interior',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'レディース',
                'slug' => 'ladies',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'メンズ',
                'slug' => 'mens',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'コスメ',
                'slug' => 'cosmetics',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => '本',
                'slug' => 'book',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'ゲーム',
                'slug' => 'game',
            ];
            DB::table('categories')->insert($param);


            $param = [
                'name' => 'スポーツ',
                'slug' => '良好',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'キッチン',
                'slug' => 'kitchen',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'ハンドメイド',
                'slug' => 'handmade',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'アクセサリー',
                'slug' => 'accessory',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'おもちゃ',
                'slug' => 'toy',
            ];
            DB::table('categories')->insert($param);

            $param = [
                'name' => 'ベビー・キッズ',
                'slug' => 'babykids',
            ];
            DB::table('categories')->insert($param);
    }
}
