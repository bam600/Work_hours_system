<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakModel;
use App\Models\Staff;
use App\Models\AttendRequest;
use App\Http\Requests\AttendanceInfoRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceInfoController extends Controller
{
    public function show(Request $request, $id)
    {
        $staffId = auth()->id();

        $attendance = Attendance::with(['staff', 'breaks', 'attendRequest'])
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->firstOrFail();

        $latestRequest = $attendance->attendRequest;
        $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

        return view('attendanceInfo', compact('attendance', 'editable', 'id'));
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

        // 出退勤時間の更新
        $attendance->clock_in = Carbon::parse("{$originalDate} {$request->input('clockin')}");
        $attendance->clock_out = Carbon::parse("{$originalDate} {$request->input('clockout')}");
        $attendance->note = $request->input('note');
        $attendance->save();

        // 既存の休憩時間を削除
        $attendance->breaks()->delete();

        // 休憩時間の更新（null対策済み）
        $breakStarts = is_array($request->input('break_start')) ? $request->input('break_start') : [];
        $breakEnds = is_array($request->input('break_end')) ? $request->input('break_end') : [];

        foreach ($breakStarts as $index => $start) {
            $end = $breakEnds[$index] ?? null;

            if (empty($start) && empty($end)) {
                continue;
            }

            $attendance->breaks()->create([
                'start_time' => Carbon::parse("{$originalDate} {$start}"),
                'end_time' => $end ? Carbon::parse("{$originalDate} {$end}") : null,
            ]);
        }

        // 申請レコードを作成（承認待ち）
        AttendRequest::updateOrCreate(
            ['attendance_id' => $attendance->id],
            [
                'staff_id' => $staffId,
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
            ]
        );

        // editable 判定（承認待ちなら false）
        $attendance->load('attendRequest');
        $latestRequest = $attendance->attendRequest;
        $editable = !($latestRequest && in_array($latestRequest->status, ['pending', 'approved']));

        // ビュー分岐
        $view = $user->is_admin ? 'adminInfo' : 'attendanceInfo';

        return view($view, compact('attendance', 'editable', 'id'))
            ->with('success', '修正して申請しました');
    }
}
