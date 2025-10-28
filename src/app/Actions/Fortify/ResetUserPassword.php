<?php

namespace App\Actions\Fortify; //このクラスはFortifyフォルダにある

//Staffモデルとつながっている
use App\Models\Staff; 
 //パスワードを暗号化する機能
use Illuminate\Support\Facades\Hash;
//入力されたデータ（新しいパスワード）のチェックをする
use Illuminate\Support\Facades\Validator; 
//「パスワードをリセットする契約（インターフェース）」を実装する
use Laravel\Fortify\Contracts\ResetsUserPasswords;

// パスワードを忘れたときに再設定する処理を担当するクラス
class ResetUserPassword implements ResetsUserPasswords
{
    // パスワードに関するルールをまとめたファイルが使える
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *@param  array<string, string>  $input
     * 
     * $staff：パスワードをリセットする対象のユーザー（Staffモデル）
     * $input：フォームから送られてきた新しいパスワードなどのデータ
     * 
     */
    public function reset(Staff $staff, array $input): void
    {

        /**
         * フォームから送られた新しいパスワードをチェック
         * $this->passwordRules() には、trait で定義されたルールが入る
         */
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();


        /**
         * パスワード保存(更新)処理
         * Hash::make()：パスワードを暗号化（ハッシュ化）
         * $user->forceFill([...])：$user モデルのカラムに値を直接代入
         * save()：データベースに保存
         * 「新しいパスワードを暗号化して上書き保存する」という動き
         */
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
