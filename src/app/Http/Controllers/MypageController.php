<?php

// PG09　プロフィール画面のコントローラ
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Exhibition;           

class MypageController extends Controller
{
    public function index(Request $request)
    {
        // 自分が出品した商品一覧を取得
        $exhibitions = Exhibition::where('user_id', Auth::id())->get();

        return view('mypage', compact('exhibitions'));
    }
}
