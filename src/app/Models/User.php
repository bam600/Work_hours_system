<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 

// 新規登録及びプロフィール登録画面用
/**
 * このモデル（たとえば User）が 1つの Profile を持つ という関係を定義します。*「親 → 子」の方向で、親モデルから子モデルを取得するためのリレーションです。
 */
class User extends Authenticatable
{
    // モデルファクトリを使用するためのトレイト。
    use HasFactory;
    // $fillableで安全に代入可能なカラムを明示。無い場合create()は使えない
    protected $fillable = ['user_name', 'email', 'password'];

    // Profileテーブルとリレーション設定(Usersが親)
    public function profile()
{
    return $this->hasOne(Profile::class);
}

// User.php
public function likes()
{
    return $this->hasMany(Like::class);
}

}
