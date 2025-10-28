<?php

namespace App\Actions\Fortify;

// 社員情報を保存するモデル（＝staffsテーブルとつながっている）
use App\Models\Staff;
// パスワードを暗号化（ハッシュ化）する
use Illuminate\Support\Facades\Hash;
// 入力内容（名前・メール・パスワード）をチェックする
use Illuminate\Support\Facades\Validator;
// 重複禁止など、細かいバリデーションルールを作る
use Illuminate\Validation\Rule;
// 「新しいユーザーを作る」ための契約（インターフェース）
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    // パスワードチェックとルールをまとめたセット
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     * @param  array<string, string>  $input
     * 
     * inputに登録フォームから送られてきたデータが入る
     * このメソッドがStaffモデルデータを返す
     */
    public function create(array $input): Staff
    {
        Validator::make($input, [
            'user_name' => ['required', 'string', 'max:255'], //名前必須　文字列255以内
            'email' => [ //メールは必須、メール形式、重複禁止
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Staff::class),
            ],
            'password' => $this->passwordRules(), //traitのルールに従う（8文字以上など）
        ])->validate();

        // ランダムでis_adminを設定(0または1)
        $isAdmin = (bool) random_int(0, 1);
        
        //登録処理
        return Staff::create([
            'user_name' => $input['user_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'is_admin' => $isAdmin,
        ]);
    }
}
