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
    $pendingRequests = Attendance::with(['staff'])
        ->where('staff_id', auth()->id())
        ->whereHas('attendRequest', function ($query) {
            $query->where('status', 'pending');
        })
        ->get();

    $approvedRequests = Attendance::with(['staff'])
        ->where('staff_id', auth()->id())
        ->whereHas('attendRequest', function ($query) {
            $query->where('status', 'approved');
        })
        ->get();

    return view('requestlist', compact('pendingRequests', 'approvedRequests'));
}
    }
