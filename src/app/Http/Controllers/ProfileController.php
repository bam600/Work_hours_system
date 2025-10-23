<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;
use App\Models\Exhibition;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    // PG09　プロフィール画面(初回)************************************************************************************************************

    /**
     * プロフィール編集画面を表示するための処理
     * GETメソッドで　/mypage/profileにアクセスしたときに呼び出される
     * resources/views/mypage/profile.blade.php を表示。
    */
    public function edit()
    {
      if (!Auth::check()) {
        return redirect()->route('login')->withErrors(['auth' => 'ログインしてください']);
      }
        $user = Auth::user()->load('profile');
        // mypage/profileにuserモデルを持って表示
        return view('mypage.profile', compact('user'));
    }


    /**
     * フォームのバリデーションルールを定義したクラス
     * バリデーション通以下したデータのみ$requestに渡される
     */
    public function update(ProfileRequest $request) //プロフィール情報の保存（POST）
  {
    /**
     * User::App\Models\User モデルを使って、users テーブルにアクセス
     * ->first():条件に一致したレコードのうち、最初の1件を取得
     * 存在しなければnullを返す
     * if (!$user)～：$userがnullだったらエラーメッセージを表示する
     * redirect()->back()：元の画面に戻る。
     * withErrors()：フォームのエラーとしてメッセージを渡す。
     * 'user_name' => '...'：user_name フィールドに対してエラーを表示。
     */
    $user = Auth::user()->load('profile');// ログインユーザー取得
    // ユーザーネームを更新
    $user->update([
      'user_name' => $request->user_name,
    ]);
    // プロフィールの更新 or 作成(firstOrNew)
    $profile = Profile::firstOrNew(['user_id' => $user->id]);
    $isNew = !$profile->exists;
    $profile->user_id = $user->id; // 明示的に設定
    $profile->postal_code = $request->postal_code;
    $profile->address     = $request->address;
    $profile->building    = $request->building;
    $profile->save();

    // 遷移先を分岐
    return $isNew
    // 新規登録だったら商品一覧画面に遷移
    ? redirect()->route('item.index')
    // 新規登録ではなかったらマイページに遷移
    : redirect()->route('mypage');
  }

  
  public function show()
    {
        $user = Auth::user();

        // ログインユーザーが出品した商品を取得
        $exhibitions = Exhibition::with('images')
            ->where('user_id', $user->id)
            ->get();

        return view('mypage', compact('exhibitions'));
    }
}


