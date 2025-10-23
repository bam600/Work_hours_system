<?php

namespace App\Http\Requests;
//Laravelの FormRequest クラスを読み込む。
use Illuminate\Foundation\Http\FormRequest;

// LoginRequest は FormRequest を継承したクラス
// このクラスはログインフォームのバリデーションを担当している。
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // authorize() メソッド
    // このリクエストを実行する権限があるかを判定するメソッド

    public function authorize()
    {
        // trueを返すことですべてのユーザーがこのリクエストが使える
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    // バリデーション設定メソッド(バリデーションルール記載)
    public function rules()
    {
        return [
            // email:入力必須
            'email' => ['required','email'],
            // 入力必須
            'password' => ['required', 'min:8'],
        ];
    }

    // バリデーションエラーメッセージの設定
    public function messages()
     {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '正しいメールアドレス形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
     }
}
