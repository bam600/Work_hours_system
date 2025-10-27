<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;             //Request：HTTPリクエストを受け取るため。
use Illuminate\Support\Facades\Auth;    //Auth：Laravelの認証ファサード。ログイン・ログアウト処理に使用
use App\Http\Controllers\Controller;   //Laravelの基本コントローラークラス。これを継承して機能を拡張

class AdminSessionController extends Controller
{
    /**
     * ユーザがログアウト処理を
     * 行ったときに呼ばれるメソッド
     */
    public function destroy(Request $request)  
    {   
        /**Laravelの認証機能で、現在ログインしている
         * ユーザーをログアウトさせる
         * 'webは通常のユーザー認証を指す
         */
        Auth::guard('web')->logout();  //ログアウト　Auth::check（）はfalse,Auth::user()はnullになる

        $request->session()->invalidate();  //セッション無効化　現在のセッション破棄
        $request->session()->regenerateToken(); // CSRFトークン再生成

        return redirect('admin/login'); // ← ログアウト後にログイン画面へ
    }
public function store(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => 'メールアドレスの形式が正しくありません。',
        'password.required' => 'パスワードを入力してください',
    ]);

    // 管理者用ガードで認証
    if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'auth' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    $request->session()->regenerate();

    return redirect()->intended('/admin/dashboard');
}

}