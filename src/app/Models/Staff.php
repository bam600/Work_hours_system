<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 新規登録及びプロフィール登録画面用
/**
 * このモデル（たとえば User）が 1つの staff を持つ という関係を定義します。*「親 → 子」の方向で、親モデルから子モデルを取得するためのリレーションです。
 */
class Staff extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    // $fillableで安全に代入可能なカラムを明示。無い場合create()は使えない
    protected $fillable = ['user_name','email','password','is_admin'];

}
