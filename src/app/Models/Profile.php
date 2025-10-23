<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/** 
 * profiles テーブルには user_id カラムがあると仮定。
 * Profile モデルのインスタンスから user を呼び出すと、
 * 関連する User モデルが取得できます。
*/
class Profile extends Model
{
    use HasFactory;

    // $fillableで安全に代入できるカラムを明示。これがないとcreate() は使えない。
    protected $fillable = ['user_id', 'user_name', 'postal_code','address','building'];

    //リレーション設定(Profileが子)
    public function user()
{
    //Eloquentのリレーションメソッド。
    return $this->belongsTo(User::class);
}

}