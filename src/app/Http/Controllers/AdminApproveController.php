<?php

// 修正承認詳細画面(管理者)
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\Staff;
use App\Models\AttendRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceInfoRequest;

use Carbon\Carbon;

class AdminApproveController  extends Controller
{

    public function show(Request $request, $id)
{
    $staffId = $request->query('staff_id');
    $user = Auth::user();

    // 管理者は staff_id を使って他人の勤怠も見られる
    if ($user->is_admin) {
    $query = Attendance::with(['staff', 'breaks', 'attendRequest'])->where('id', $id);
    if ($staffId) {
        $query->where('staff_id', $staffId);
    }
    $attendance = $query->firstOrFail();
    
    } else {
    $attendance = Attendance::with(['staff', 'breaks', 'attendRequest'])
        ->where('id', $id)
        ->where('staff_id', $user->id)
        ->firstOrFail();
    }

    $latestRequest = $attendance->attendRequest;
    $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

    return view('approve', compact('attendance', 'editable', 'id'));
    }


 // 承認処理
    public function approve(Request $request, $attendance_correct_request_id)
{
    $user = Auth::user();

    if (!$user->is_admin) {
        abort(403, 'アクセス権限がありません');
    }

    $requestRecord = AttendRequest::findOrFail($attendance_correct_request_id);
    $requestRecord->status = 'approved';
    $requestRecord->approved_by = $user->id;
    $requestRecord->approved_at = now();
    $requestRecord->save();

    // Attendance を取得
    $attendance = Attendance::with(['staff','breaks','attendRequest'])
        ->where('id', $requestRecord->attendance_id) // AttendRequest に attendance_id がある前提
        ->firstOrFail();

    $latestRequest = $attendance->attendRequest;
    $editable = !($latestRequest && in_array($latestRequest->status, ['pending','approved']));

    return view('approve', compact('attendance','editable','attendance_correct_request_id'));
}
}

