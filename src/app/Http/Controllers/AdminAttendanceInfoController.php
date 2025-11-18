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

    return view('adminInfo', compact('attendance', 'editable', 'id'));
    }

    public function submit(AttendanceInfoRequest $request, $id)
{
    $user = Auth::user();
    $staffId = $request->input('staff_id', $user->id); // 管理者なら他人のIDも渡せる

    $attendance = Attendance::with('breaks')
        ->where('id', $id)
        ->where('staff_id', $staffId)
        ->firstOrFail();

    $originalDate = Carbon::parse($attendance->clock_in)->format('Y-m-d');

    // 出退勤時間の更新
    $attendance->clock_in = Carbon::parse("{$originalDate} {$request->input('clockin')}");
    $attendance->clock_out = Carbon::parse("{$originalDate} {$request->input('clockout')}");

    // 備考の更新
    $attendance->note = $request->input('note');

    $attendance->save();

    $attendance->breaks()->delete();

    // 休憩時間の更新
    $breakStarts = $request->input('break_start');
    $breakEnds = $request->input('break_end');

foreach ($breakStarts as $index => $start) {
    $end = $breakEnds[$index] ?? null;

    if (empty($start) && empty($end)) {
        continue;
    }

        $attendance->breaks()->create([
        'start_time' => Carbon::parse("{$originalDate} {$start}"),
        'end_time' => Carbon::parse("{$originalDate} {$end}"),
    ]);
    }

    // 申請レコードを作成（承認待ち）
AttendRequest::updateOrCreate(
    ['attendance_id' => $attendance->id],
    ['staff_id' => $staffId, 'status' => 'pending']
);

    // editable 判定（承認待ちなら false）
    $attendance->load('attendRequest');
    $latestRequest = $attendance->attendRequest;
    $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

    // ビュー分岐
    if (!$user->is_admin) {
        return view('attendanceInfo', compact('attendance', 'editable', 'id'))
            ->with('success', '修正して申請しました');
    } else {
        return view('adminInfo', compact('attendance', 'editable', 'id'))
            ->with('success', '修正して申請しました');
    }
}


 // 申請レコードを作成（承認済み）
public function approve(Request $request, $id)
{
    $user = Auth::user();

    if (!$user->is_admin) {
        abort(403, 'アクセス権限がありません');
    }

    $attendance = Attendance::with('attendRequest')->findOrFail($id);

    $requestRecord = $attendance->attendRequest;

    if ($requestRecord && $requestRecord->status === 'pending') {
        $requestRecord->status = 'approved';
        $requestRecord->save();
    }

    return redirect()->route('admininfo.show', ['id' => $id])
        ->with('success', '申請を承認しました');

}
}