<?php
// 勤怠時間管理モデル
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    // $fillableで安全に代入可能なカラムを明示。無い場合create()は使えない
    protected $fillable = [
                'staff_id',
                'status',
                'clock_in',
                'clock_out',
                'actual_work_time',
                'created_at',
                'updated_at',
            ];

    // 勤怠記録とのリレーション（1対多）
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    //休憩時間とのリレーション(多対多)
    public function breaks()
    {
        return $this->hasMany(BreakModel::class, 'attendance_id');
    }

}
