<?php
// 商品出品用controller
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Exhibition;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Condition;

// 商品出品に関する 画面表示（create）と保存処理（store）を行うクラス
class CreateController extends Controller
{
    //createメソッド
    public function create()
    {   
        //カテゴリー一覧の取得(チェックボックス表示用)
        $categories = Category::all(); 
        // 商品状態一覧の取得(セレクトボックス表示用)
        $conditions = Condition::all();

        // 新規出品用の空のExhibitionインスタンスを渡す
        $exhibition = new Exhibition();

        // 取得した値を返す
        return view('item.create', compact('exhibition', 'conditions', 'categories'));
    }

    // storeメソッド
    public function store(ExhibitionRequest $request)
    {
        // バリデーション済みの値を取得
        $validated = $request->validated();

        // Exhibitionテーブルに保存
        $exhibition = Exhibition::create([
            'product_name'  => $validated['product_name'],
            'brand'         => $validated['brand'],
            'condition_id'  => $validated['condition_id'],
            'description'   => $validated['description'],
            'price'         => $validated['price'],
            'user_id'       => Auth::id(),
            'status'        => 'published',
        ]);

        // 中間テーブルへのカテゴリー接続責務
        if (isset($validated['categories'])) {
            $exhibition->categories()->attach($validated['categories']);
        }

        return redirect()->route('mypage')->with('status', '商品を出品しました。'); 
    }

}