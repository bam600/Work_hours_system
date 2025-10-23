<?php
// 出品商品閲覧用controller
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class ExhibitionController extends Controller
{
    public function myLikedItems()
{
    $userId = Auth::id();

    // ログインユーザーがいいねした exhibition_id を取得
    $likedIds = Like::where('user_id', $userId)->pluck('exhibition_id');

    // それに該当する Exhibition を取得（画像やlikesも一緒に）
    $exhibitions = Exhibition::with(['images', 'likes'])
        ->whereIn('id', $likedIds)
        ->get();

    return view('exhibitions.mylist', compact('exhibitions'));
}
}
