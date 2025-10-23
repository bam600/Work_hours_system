<?php

/**PG05　商品詳細画面専用コントローラー */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Condition;

class ItemController extends Controller
{
    // 商品詳細画面を表示するメソッド
    public function show($id)
    {
        /**
         * 指定された商品の情報を1件取得
         * with(['categories', 'condition']) → 関連テーブルも一緒に取得
         * （リレーション）
         * withCount('likes') → いいね数（likes_count）を自動で追加
         * findOrFail($id) → 該当するIDの商品を探す。なければ404エラー
         * （自動でNotFound画面）
         */
        $exhibition = Exhibition::with(['categories', 'condition'])
            ->withCount('likes')
            ->findOrFail($id);

        /**取得した商品に紐づくカテゴリ情報を取り出す
         *$exhibition->categories は、Exhibitionモデルで定義した
         *リレーションから呼び出せる
         */
        $categories = $exhibition->categories;

    /**
     *データをビュー(item/show.blade.php)に渡して表示する
     *compact('exhibition', 'categories') で変数をまとめてビューに送る
     * */
    return view('item.show', compact('exhibition', 'categories')); 
}


    // 商品一覧・検索
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $tab = $request->input('tab');

        if ($tab === 'recommend') {
            $exhibitions = Exhibition::all();
            $keyword = '';
        } elseif ($tab === 'mylist') {
            $keyword = session('last_search') ?? '';
            $exhibitions = !empty($keyword)
                ? Exhibition::where('product_name', 'like', "%{$keyword}%")->get()
                : collect();
        } elseif (!empty($keyword)) {
            session(['last_search' => $keyword]);
            $exhibitions = Exhibition::where('product_name', 'like', "%{$keyword}%")->get();
        } else {
            $exhibitions = Exhibition::all();
        }

        return view('home', compact('exhibitions', 'keyword', 'tab'));
    }

    public function preparePurchase($id)
    {
    $exhibition = Exhibition::findOrFail($id);

    // 購入に必要な情報だけセッションに保存
    session([
        'selected_exhibition' => [
            'id' => $exhibition->id,
            'product_name' => $exhibition->product_name,
            'price' => $exhibition->price,
        ]
    ]);

    session()->save(); // セッション保存を明示

    return redirect()->route('item.purchase.confirm');
    }
}
