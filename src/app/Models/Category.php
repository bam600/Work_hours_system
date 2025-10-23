<?php
// Categoryテーブル
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id','name','slug']; // 必要に応じて追加

    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class);
    }
}
