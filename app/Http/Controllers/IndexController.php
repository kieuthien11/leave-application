<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Handle the request and return the appropriate view based on user role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check the user's role
            if (Auth::user()->role == 'admin') {
                // If admin, return the user management page
                return redirect()->route('user.index'); // or return view('user.index');
            }
            
            $user = Auth::user();

            // Lấy thông tin nghỉ phép của người dùng
            $leaveBlance = LeaveBalance::where('employee_id', $user->employee_id)
                ->first();
            $remainingLeave = $leaveBlance->remaining_days;

            $usedLeave = LeaveRequest::where('employee_id', $user->employee_id)
                ->where('status', 'approved')
                ->whereYear('start_date', Carbon::now()->year)
                ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));

            // Lấy số đơn nghỉ phép đang chờ duyệt và bị từ chối
            $pendingRequests = LeaveRequest::where('employee_id', $user->employee_id)
                ->where('status', 'pending')
                ->count();

            $rejectedRequests = LeaveRequest::where('employee_id', $user->employee_id)
                ->where('status', 'rejected')
                ->count();
          
            // If the user is a manager or staff, return the dashboard
            return view('index', [
                'user' => $user,
                'usedLeave' => $usedLeave,
                'remainingLeave' => $remainingLeave,
                'pendingRequests' => $pendingRequests,
                'rejectedRequests' => $rejectedRequests,
            ]); // Dashboard view for manager/staff
        }

        // If not authenticated, redirect to login page (optional)
        return redirect()->route('login');
    }
}
