<?php
// attendancerequestモデル
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\AttendRequest;

class AttendRequest extends Model
{
    protected $table = 'attendrequests';

    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'staff_id',
        'status',
        'approved_by',
        'approved_at',
        ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // 承認者(管理者)の情報取得用
    public function approver()
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

}