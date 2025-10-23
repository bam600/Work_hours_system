<?php
/**支払い管理テーブル*/
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    /**取得カラム */
    protected $fillable = ['id', 'name', 'code'];
}
