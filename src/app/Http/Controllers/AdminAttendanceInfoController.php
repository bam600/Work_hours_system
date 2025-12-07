<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceInfoRequest;
use Carbon\Carbon;

class AdminAttendanceInfoController extends Controller
{
    public function show(Request $request, $id)
    {
        $staffId = $request->query('staff_id');
        $user = Auth::user();

        // 管理者は他人の勤怠も見られる
        $query = Attendance::with(['staff', 'breaks', 'attendRequest'])->where('id', $id);

        if (!$user->is_admin) {
            $query->where('staff_id', $user->id);
        } elseif ($staffId) {
            $query->where('staff_id', $staffId);
        }

        $attendance = $query->firstOrFail();
        $latestRequest = optional($attendance->attendRequest);
        $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

        return view('adminInfo', compact('attendance', 'editable', 'id'));
    }

    public function submit(AttendanceInfoRequest $request, $id)
    {
        $user = Auth::user();
        $staffId = $request->input('staff_id', $user->id);

        $attendance = Attendance::with('breaks')
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->firstOrFail();

        $originalDate = Carbon::parse($attendance->clock_in)->format('Y-m-d');

        // 勤怠情報の更新
        $attendance->clock_in = Carbon::parse("$originalDate {$request->input('clockin')}");
        $attendance->clock_out = Carbon::parse("$originalDate {$request->input('clockout')}");
        $attendance->note = $request->input('note');
        $attendance->save();

        // 既存の休憩時間を削除
        $attendance->breaks()->delete();

        // 休憩時間の更新（null対策済み）
        $breakStarts = is_array($request->input('break_start')) ? $request->input('break_start') : [];
        $breakEnds = is_array($request->input('break_end')) ? $request->input('break_end') : [];

        foreach ($breakStarts as $i => $start) {
            $end = $breakEnds[$i] ?? null;

            if (empty($start) && empty($end)) {
                continue;
            }

            $attendance->breaks()->create([
                'start_time' => Carbon::parse("$originalDate $start"),
                'end_time'   => $end ? Carbon::parse("$originalDate $end") : null,
            ]);
        }

        // 修正申請の作成または更新（常にpending）
        AttendRequest::updateOrCreate(
            ['attendance_id' => $attendance->id],
            [
                'staff_id' => $staffId,
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
            ]
        );

        return redirect()->route('admininfo.show', ['id' => $id])
            ->with('success', '修正して申請しました');
    }

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
            $requestRecord->approved_by = $user->id;
            $requestRecord->approved_at = now();
            $requestRecord->save();
        }

        return redirect()->route('admininfo.show', ['id' => $id])
            ->with('success', '申請を承認しました');
    }
}
