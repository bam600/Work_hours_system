<?php

namespace App\Actions\Fortify;

use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

// 「ログイン中のユーザーが、パスワードを変更するとき」 に使われる処理クラス
class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(Staff $staff, array $input): void
    {
        // 入力内容のチェック(バリデーション)
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');
        
        // パスワードを更新して保存
        $staff->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
