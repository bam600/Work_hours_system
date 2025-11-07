<?php
/**
 * ログイン・ログアウト認証-----------------------------------
 * このコントローラは App\Http\Controllers\Auth 名前空間に属する
 * Laravel Fortify の認証関連コントローラーと同じ構造にすることで
 * Fortifyのルート定義と自然に連携できる
*/
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;             //Request：HTTPリクエストを受け取るため。
use Illuminate\Support\Facades\Auth;    //Auth：Laravelの認証ファサード。ログイン・ログアウト処理に使用
use App\Http\Controllers\Controller;   //Laravelの基本コントローラークラス。これを継承して機能を拡張
use App\Models\Profile;



class AuthenticatedSessionController extends Controller
{   
        public function create(Request $request)  
    {   
        return view('auth.login'); // ← ログアウト後にログイン画面へ
    }

    public function showAdminLoginForm()
    {
    return view('auth.adminlogin'); // ← 管理者用ログイン画面
    }


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

        return redirect('/login'); // ← ログアウト後にログイン画面へ
    }

    
public function store(Request $request)
{
    // バリデーション（入力チェック）
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => 'メールアドレスの形式が正しくありません。',
        'password.required' => 'パスワードを入力してください',
    ]);

    // 認証チェック（ログイン試行）
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'auth' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    $user = Auth::user();
    $request->session()->regenerate();

    \Log::debug('ログインユーザー:', ['id' => $user->id, 'is_admin' => $user->is_admin]);
    \Log::debug('アクセスパス:', ['path' => $request->path()]);

    // アクセス元URLで分岐！
    if (str_starts_with($request->path(), 'admin')) {
    if ((int)$user->is_admin !== 1) {
        Auth::logout();
        return back()->withErrors([
            'auth' => '管理者のみログイン可能です',
        ])->withInput();
    }
    return redirect('/attendance/list');
}

    // 一般ユーザー用の遷移
    return redirect('/attendance');
}
}