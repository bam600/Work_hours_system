<?php
namespace App\Providers;

// 新しいユーザーを登録するクラス
use App\Actions\Fortify\CreateNewUser;
// パスワードをリセットするクラス
use App\Actions\Fortify\ResetUserPassword;
// パスワードを変更するクラス
use App\Actions\Fortify\UpdateUserPassword;
// ユーザー情報（名前やメール）を更新するクラス
use App\Actions\Fortify\UpdateUserProfileInformation;
//Limit：どれくらいの回数を許可するか決めるクラス
use Illuminate\Cache\RateLimiting\Limit;
//ユーザーから送られてきた情報（メールアドレスやパスワード）を扱う
use Illuminate\Http\Request;
//一定時間にログインを何回まで許すかを制限する（セキュリティ対策）
use Illuminate\Support\Facades\RateLimiter;
// Laravelの設定をまとめるクラス（親クラス）
use Illuminate\Support\ServiceProvider;
//文字列を操作する便利な関数をまとめたクラス
use Illuminate\Support\Str;
//ログイン・登録・パスワード変更などの機能を管理する本体
use Laravel\Fortify\Fortify; 

// Laravel Fortify（ログイン・登録などの認証機能ライブラリ）を設定するクラス
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * アプリを使うサービスを登録する場所
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * アプリが起動するときよばれ、FOrtifyの設定が
     * 読み込まれる
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class); //新規登録処理
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class); //プロフィール更新
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class); // パスワード変更
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class); //パスワードリセット

        /**
         * 連続ログインをして試す悪意のある攻撃から守る設定
         */
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        //どの画面を使うかを教える設定(login.blade)
        Fortify::loginView(function () {
            return view('auth.login');
});
    }
}
