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
    public function loginshow()
    {
        return view('auth.login');
    }

    // POST /login に送信されたフォームデータを受け取るメソッド。
    //LoginRequest によって、バリデーションは事前に済んだ状態で呼び出されます。
    public function store(LoginRequest $request)
    {
        //フォームから送られた email と password を抽出
        // only() は指定したキーだけを取り出す Laravel の便利メソッド
        // $credentialsに入力フォームで入力されたメールとパスワードが代入される
        $credentials = $request->only('email', 'password');

        // Auth::attempt() により、認証を試みます。
        // 成功すれば / にリダイレクト。
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }
        // 認証失敗時の処理(登録情報と異なる)
        return back()->withErrors([
            'auth' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}