<?php
// いいね専用コントローラー
namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($id)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'exhibition_id' => $id,
        ]);
        return back();
    }

    public function destroy($id)
    {
        Like::where('user_id', Auth::id())
            ->where('exhibition_id', $id)
            ->delete();
        return back();
    }
}