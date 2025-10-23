<?php
// 商品出品用テーブルのダミーデータ(仕様書参考の商品)
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exhibition;

class ExhibitionSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [   
                'user_id' => 2,
                'product_name' => '腕時計',
                'brand' => 'Rolax',
                'condition_id' => 1, //良好
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'status' => 'published', //販売中
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => 2,
                'product_name' => 'HDD',
                'brand' => '西芝',
                'condition_id' => 2, //目立った傷や汚れなし
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'status' => 'sold', //売り切れ
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                
            ],
            [
                'user_id' => 3,
                'product_name' => '玉ねぎ3束',
                'brand' => 'なし',
                'condition_id' => 3, //やや傷やよごれあり
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'status' => 'sold', //売り切れ    
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            ],

            [
                'user_id' => 3,
                'product_name' => '革靴',
                'brand' => null,
                'condition_id' => 4, //状態が悪い
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'status' => 'published', //販売中    
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            ],

            [
                'user_id' => 3,
                'product_name' => 'ノートPC',
                'brand' => null,
                'condition_id' => 1, //良好
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'status' => 'sold', //売り切れ
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            ],

            [
                'user_id' => 4,
                'product_name' => 'マイク',
                'brand' => 'なし',
                'condition_id' => 2, //目立った傷や汚れあり
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'status' => 'published', //販売中    
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            ],

            [
                'user_id' => 4,
                'product_name' => 'ショルダーバッグ',
                'brand' => null,
                'condition_id' => 3, //やや傷やよごれあり
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'status' => 'sold', //売り切れ
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            ],

            [
                'user_id' => 4,
                'product_name' => 'タンブラー',
                'brand' => 'なし',
                'condition_id' => 4, //状態が悪い
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'status' => 'sold', //売り切れ
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            ],

            [
                'user_id' => 5,
                'product_name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'condition_id' => 1, //良好                
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'status' => 'published', //販売中
                'img' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            ],

            [
                'user_id' => 5,
                'product_name' => 'メイクセット',
                'brand' => null,
                'condition_id' => 2, //目立った傷やよごれなし                
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'status' => 'published', //販売中
                'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            ],
    
        ];

        foreach ($items as $item) {
            Exhibition::create($item);
        }
    }
}
