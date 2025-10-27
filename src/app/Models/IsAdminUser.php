<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsAdminUser extends Model
{
    use HasFactory;

    protected $table = 'is_admin_users'; // テーブル名が複数形でない場合は明示

    protected $fillable = [
        'staff_id',
        'employee_number',
        'is_admin',
    ];

    // Staffとのリレーション（staff_idベース）
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // 社員番号ベースのリレーション（必要なら）
    public function staffByEmployeeNumber()
    {
        return $this->belongsTo(Staff::class, 'employee_number', 'employee_number');
    }
}