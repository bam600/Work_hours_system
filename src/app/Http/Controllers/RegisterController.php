<?php

// PG01　会員登録画面(register)*******************************************************************************************************

/**
 * ユーザー会員登録画面の表示
 * GET　/registerにアクセスされたときに呼び出される
 * resources/views/register.blade.php を返す
 */

//クラスの(名前空間)を定義する宣言
namespace App\Http\Controllers;  

//Staff::create() で使用　※DB保存
use App\Models\Staff;
// store(RegisterRequest $request)使用　※バリデーション
use App\Http\Requests\RegisterRequest;
// Faker::create()で使用　※社員番号生成で使用
use Faker\Factory as Faker;
// Hash::make() でパスワードを暗号化で使用　※パスワード暗号化に使用
use Illuminate\Support\Facades\Hash;


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
   public function show()
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

      // Fakerはダミーデータ自動生成ライブラリLaravel標準で利用可）。
      $faker = Faker::create();

      // bothify('??#####??') は、ランダムな英数字の並びを作るメソッド
      // 同じ社員番号がすでに使われていたら、もう一回作り直すループ
      do {
         $employeeNumber = $faker->bothify('??#####??');
      } while (Staff::where('employee_number', $employeeNumber)->exists());

      // Staff モデルを使って、staffs テーブルに新しいデータを保存
      $staff = Staff::create([
         'user_name' => $validated['user_name'],
         'email' => $validated['email'], // パスワードをハッシュ化する

         'employee_number' => $employeeNumber,
         // パスワードは暗号化して保存
         'password' => Hash::make($validated['password']), 
      ]);
         // 登録後ルート名attendance(/attendance)に遷移
         return redirect()->route('attendance'); 
}
      

   }

