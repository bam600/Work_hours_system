<?php
// 勤怠詳細画面(管理者)
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\Staff;
use App\Models\AttendRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceInfoRequest;

use Carbon\Carbon;

class AdminAttendanceInfoController  extends Controller
{

    public function show(Request $request, $id)
{
    $staffId = $request->query('staff_id');
    $user = Auth::user();

    // 管理者は staff_id を使って他人の勤怠も見られる
    if ($user->is_admin && $staffId) {
        $attendance = Attendance::with(['staff', 'breaks', 'attendRequest'])
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->firstOrFail();
    } else {
        // 一般スタッフは自分の勤怠のみ
        $attendance = Attendance::with(['staff', 'breaks', 'attendRequest'])
            ->where('id', $id)
            ->where('staff_id', $user->id)
            ->firstOrFail();
    }

    $latestRequest = $attendance->attendRequest;
    $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

    return view('attendanceInfo', compact('attendance', 'editable', 'id'));
    }
}