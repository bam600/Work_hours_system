<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Break;
use App\Models\Staff;

class AttendanceInfoController extends Controller
{
    public function show($id)
    {
        $attendance = Attendance::with(['staff', 'breaks'])
            ->where('staff_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('attendanceInfo', compact('attendance'));
    }
}
