<?php
// 休憩時間管理モデル
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakModel extends Model
{   
    protected $table = 'breaks';

    use HasFactory;
    // $fillableで安全に代入可能なカラムを明示。無い場合create()は使えない
        protected $fillable = [
            'attendance_id',
            'start_time',
            'end_time',
        ];

    // 勤怠時間管理テーブルとのリレーション
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

}
