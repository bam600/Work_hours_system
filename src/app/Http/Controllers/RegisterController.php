<?php

// PG01　会員登録画面(register)

/**
 * ユーザー会員登録画面の表示
 * GET　/registerにアクセスされたときに呼び出される
 * resources/views/register.blade.php を返す
 */

//クラスの(名前空間)を定義する宣言
namespace App\Http\Controllers;  

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
//Staff::create() で使用　※DB保存
use App\Models\Staff;
// store(RegisterRequest $request)使用　※バリデーション
use App\Http\Requests\RegisterRequest;
// Hash::make() でパスワードを暗号化で使用　※パスワード暗号化に使用
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;

/**
 * RegisterController は Laravel のベースコントローラー Controller を継承
 * ユーザー登録に関する処理（画面の表示・データの保存）をまとめたクラス
 **/
class RegisterController extends Controller
{
   /** 
    *  showメソッド:登録画面の表示
    *  GET /registerにアクセスしたときに呼び出されるメソッド  (localhost/でアクセス後)
    *  resources/views/register.blade.php を表示
    */
   public function create()
   {
      return view('register'); 
   }

   /**
    * storeメソッド：登録処理(保存)
    *RegisterRequest というクラス（別ファイル）で
    *バリデーションルール（必須・文字数など）を自動チェック    
    */

   public function store(RegisterRequest $request)
   {
      /* $request->validated() によって、チェックに通ったデータだけが $validated に入る*/
      $validated = $request->validated();

      // Staff モデルを使って、staffs テーブルに新しいデータを保存
      $staff = Staff::create([
         'user_name' => $validated['user_name'],
         'email' => $validated['email'], // パスワードをハッシュ化する
         // パスワードは暗号化して保存
         'password' => Hash::make($validated['password']), 
         'is_admin'  => rand(0, 1), // ランダムで 0 または 1 を設定
      ]);

      // 送信時間をDBに保存（認証完了ではなく送信時間）
      $staff->email_sent_at = Carbon::now();
      $staff->save();

      // メール認証
      event(new Registered($staff));

       //登録したユーザーをログイン状態にする
      Auth::login($staff);

       // 登録後ルート名attendance(/attendance)に遷移
      return redirect()->route('verification.notice'); 
}
   }

