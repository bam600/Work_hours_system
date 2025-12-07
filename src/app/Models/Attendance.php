<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staff;
use App\Models\BreakModel;
use App\Models\AttendRequest;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'staff_id',
        'status',
        'clock_in',
        'clock_out',
        'note',
        'actual_work_time',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    // スタッフ（多対1）
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // 休憩（1対多）
    public function breaks()
    {
        return $this->hasMany(BreakModel::class, 'attendance_id');
    }

    // 修正申請（1対1）
    public function attendRequest()
    {
        return $this->hasOne(AttendRequest::class, 'attendance_id');
    }
}
