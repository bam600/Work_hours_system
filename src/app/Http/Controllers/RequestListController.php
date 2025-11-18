<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendRequest;
use App\Models\BreakModel;
use App\Models\Attendance;
use Carbon\Carbon;



class RequestListController extends Controller
{
    public function create(Request $request)
{
    $user = auth()->user();

    if ($user->is_admin === 1) {
        // 管理者：全スタッフの申請状況を取得
        $pendingRequests = Attendance::with(['staff', 'attendRequest'])
            ->whereHas('attendRequest', function ($query) {
                $query->where('status', 'pending');
            })
            ->get()
            ->groupBy('staff_id');

        $approvedRequests = Attendance::with(['staff', 'attendRequest'])
            ->whereHas('attendRequest', function ($query) {
                $query->where('status', 'approved');
            })
            ->get()
            ->groupBy('staff_id');

        return view('requestlist', compact('pendingRequests', 'approvedRequests'));

    } else {
        // 一般ユーザー：自分の申請状況だけ取得
        $pendingRequests = Attendance::with(['staff'])
            ->where('staff_id', $user->id)
            ->whereHas('attendRequest', function ($query) {
                $query->where('status', 'pending');
            })
            ->get();

        $approvedRequests = Attendance::with(['staff'])
            ->where('staff_id', $user->id)
            ->whereHas('attendRequest', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        return view('requestlist', compact('pendingRequests', 'approvedRequests'));
    }
    }
}