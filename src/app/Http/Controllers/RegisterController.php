<?php

// PG03　会員登録画面(register)*******************************************************************************************************

/**
 * ユーザー会員登録画面の表示
 * GET　/registerにアクセスされたときに呼び出される
 * resources/views/register.blade.php を返す
 */

//クラスの(名前空間)を定義する宣言
namespace App\Http\Controllers;  

//laravelの認証機能(ログイン・ログアウト)を操作するためのファザード(ファザード→便利な窓口でクラスを簡単につかえるようにしてくれる)
use Illuminate\Support\Facades\Auth;
//パスワードなどを安全に暗号化（ハッシュ化）するためのファサード 
use Illuminate\Support\Facades\Hash;   
//テーブルを連携するEloquentモデル。ユーザー情報の保存・取得に使う
use App\Models\User;
//フォーム入力のバリデーションルールを定義したクラス。store() メソッドの引数として使用。
use App\Http\Requests\RegisterRequest;
//ログインフォーム用のバリデーションルールを定義したクラス
use App\Http\Requests\LoginRequest;


/**
 * RegisterController は Laravel のベースコントローラー Controller を継承
 * このクラスは、ユーザー登録に関する『画面表示』と『登録処理』２つを担当
 **/
class RegisterController extends Controller
{


   /** 
    *  showメソッド:登録画面の表示
    */

    //GET /registerにアクセスしたときに呼び出されるメソッド  (localhost/でアクセス後)
   public function show()
   {
      //resources/views/register.blade.php を表示
      return view('register'); 
   }

   /** 
    *  storeメソッド:登録処理
    */

   //POST 会員登録ボタンを押した後の処理　バリデーションチェックで問題なく通過した場合のみ変数$requestに渡される
   public function store(RegisterRequest $request) 
   {
      
      //users テーブルに新しいレコードを追加
      $validated = $request->validated();

      // バリデーション通過積みのユーザー入力値
      // UserはEloquentモデル　create()は渡された配列を使って新しいレコードを作成
      $user = User::create([
         'user_name' => $validated['user_name'],
         'email' => $validated['email'], 
          // Hash::make() は Bcrypt を使って安全に暗号化する
         'password' => Hash::make($validated['password']),
      ]);
      // ※モデル側で $fillable に user_name, email, password が設定されている必要有
      
      //作成したユーザがログイン状態になる(セッション開始)
      Auth::login($user);
       //route('profile.edit') で /mypage/profile にリダイレクト
      return redirect()->route('profile.edit');
   }
}