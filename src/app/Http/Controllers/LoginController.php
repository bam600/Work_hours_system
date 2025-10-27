<?php
// - このクラスが属する名前空間は App\Http\Controllers
namespace App\Http\Controllers;

// - Auth：Laravelの認証ファサード。Auth::attempt() などでログイン処理を行う。
use Illuminate\Support\Facades\Auth;
// LoginRequest：バリデーションルールを定義したフォームリクエスト。loginstore() の引数で使用されます。
use App\Http\Requests\LoginRequest;

//LoginController は Laravel のベースコントローラー Controller を継承。
// このクラスはログイン画面の表示とログイン処理の2つの責務を持つ。
class LoginController extends Controller
{   
    //GET /login にアクセスされたときに呼び出されるメソッド。
    // resources/views/auth/login.blade.php を表示します。
    public function Loginshow()
    {
        return view('auth.login');
    }
    
    public function Loginstore()
    {
        
        return view('auth.login');
    }

}