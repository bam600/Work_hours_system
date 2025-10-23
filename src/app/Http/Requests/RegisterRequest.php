<?php

// このクラスが属する名前空間。laravelでは通常App\Http\Requestsに配置
namespace App\Http\Requests;
// laravelのFormRequestクラスを継承するために必要な宣言
use Illuminate\Foundation\Http\FormRequest;

// RegisterRequestクラスの定義FormRequestを継承してバリデーションルールや
// メッセージを定義する
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // このリクエストを実行する権限有無を判定するメソッド
    public function authorize()
    {
        // trueを返しているので誰でも使用できる状態
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    // PG03 会員登録画面：バリデーション設定メソッド(ルール記載)
    public function rules()
    {
        return [
            // username:入力必須、20文字以内
            'user_name' => ['required', 'max:20'],
            // email:入力必須、メール形式、unique-.メールアドレス重複不可
            'email' => ['required', 'email','unique:users,email'],
            // 入力必須、8文字以上、「パスワード」との重複のみ可
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }

    // バリデーションエラーメッセージの設定
    public function messages()
    {
        return [
            'user_name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email'=>'メールアドレスはメールアドレス形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは８文字以上で入力してください',
            'password.confirmed' => 'パスワードと一致しません'
        ];

    }
}
