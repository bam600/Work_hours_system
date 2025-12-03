<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Staff;   // ★ staffモデルを使用する

class RegisterTest extends TestCase
{
    use RefreshDatabase;  // ★ テストDBを毎回リセットして安全に実行できる

    /** @test */
    public function 名前が未入力の場合はエラーになる()
    {
        $response = $this->post('/register', [
            'user_name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'user_name' => 'お名前を入力してください',
        ]);
    }

    /** @test */
    public function メール未入力の場合はエラーになる()
    {
        $response = $this->post('/register', [
            'user_name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /** @test */
    public function パスワードが8文字未満の場合はエラーになる()
    {
        $response = $this->post('/register', [
            'user_name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    /** @test */
    public function パスワードが不一致の場合はエラーになる()
    {
        $response = $this->post('/register', [
            'user_name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'abc',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    /** @test */
    public function パスワード未入力の場合はエラーになる()
    {
        $response = $this->post('/register', [
            'user_name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /** @test */
    public function 正しい入力の場合はデータベースに保存される()
    {
        $response = $this->post('/register', [
            'user_name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // リダイレクトが成功するか
        $response->assertStatus(302);

        // データベースに保存されているかチェック
        $this->assertDatabaseHas('staffs', [
            'email' => 'test@example.com',
            'user_name' => 'テスト太郎',
        ]);
    }
}
