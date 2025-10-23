<?php
// 商品出品テーブルのEloquent Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Condition;
use App\Models\Like;

// Exhibitionクラスはlaravelのmodelクラスを継承
class Exhibition extends Model
{   
    // モデルファクトリ機能を使用する為のトレイト
    use HasFactory;

    // fillable：ホワイトリスト方式で一括代入許可フィールドの定義
    protected $fillable = 
                ['product_name', 
                    'brand',
                    'condition_id',
                    'description',
                    'price',
                    'status',
                    'user_id',
                    'img' 
                ];

    // 商品カテゴリーテーブルとのリレーション設定
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // app/Models/Exhibition.php
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // いいねテーブルとのリレーション(1対多)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

public function isLikedBy($user)
{
    return $this->likes()->where('user_id', $user->id)->exists();
}

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

        public function confirm(Request $request)
    {
        
    }
}

