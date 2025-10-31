<?php
// Staffs(社員管理テーブル)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            // ID
            $table->id();
            // 社員名
            $table->string('user_name', 255);
            // メールアドレス
            $table->string('email', 255)->unique();
            // パスワード
            $table->string('password', 255)->unique();;
            // 管理者権限判断　0:社員　1:管理者
            $table->boolean('is_admin')->default(false);
            // Fortify 2要素認証カラム
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            // タイムスタンプ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
