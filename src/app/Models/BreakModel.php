<?php
// 休憩時間管理モデル
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakModel extends Model
{
    use HasFactory;
    // $fillableで安全に代入可能なカラムを明示。無い場合create()は使えない
    protected $fillable = ['staff_id','clock_in','clock_out','actual_work_time'];

    // 社員テーブルとのリレーション（1対多）
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // 勤怠時間管理テーブルとのリレーション
    public function attendances()
    {
    return $this->belongsToMany(Attendance::class);
    }

}
