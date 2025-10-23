<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Exhibition;
use App\Models\Like;


class HomeController extends Controller
{

//PG01　商品一覧画面(トップ画面)**********************************************************************************************
    
public function index(Request $request)
{   
    /**
     * フロント側（BladeでURLパラメータ ?tab=○○を送ってきたときに値を
     * 受け取る部分
     * /home?tab=mylist → $tab の中身は "mylist"
     * /home（パラメータなし） → $tab の中身は 'recommend'（デフォルト）
     */
    $tab = $request->input('tab', 'recommend');

    
    /**
     * おすすめ商品一覧(おすすめ商品をすべて取得)
     * 
     * with(['likes'])
     * 「likes」リレーション（＝この商品が誰に「いいね」されているかを
     * 一緒に読み込む。
     * with() を使うと、Eloquentが必要なデータをまとめて一度に取得
     * 
     */
    $exhibitions = Exhibition::with(['likes'])->withCount('likes')->get();


    /**
     * マイリスト用の初期化（未ログインでも空コレクションを渡す）
     * 未ログインの人でもビュー側（Blade）で安全に扱えるようになる。
     */
    $likedProducts = collect();

    /**ログインしている人だけの処理 */
    if (Auth::check()) {
        /**likeテーブルからログインユーザのいいねした商品IDを全て取り出す
         * pluck('exhibition_id') は「指定したカラムだけを取り出す」
         * メソッド。
         */
        $likedIds = Like::where('user_id', Auth::id())->pluck('exhibition_id');
        /**
         * 上で取得した likedIds の商品だけを、Exhibitionから取り出す。
         * whereIn('id', $likedIds) は、「idがこのリストの中に
         * あるものを取る」という意味。
         * 「ログインユーザーがいいねした商品」だけを集めた一覧です。
         */
        $likedProducts = Exhibition::with(['likes'])->withCount('likes')
            ->whereIn('id', $likedIds)
            ->get();
    }
    /**
     * ビュー（resources/views/home.blade.php）にデータを渡す
     * compact() は変数名を文字列でまとめて配列にする関数。
     */
    return view('home', compact('exhibitions', 'likedProducts', 'tab'));
    }
}