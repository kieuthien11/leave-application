<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Constructor để đảm bảo chỉ người dùng có quyền mới truy cập
    public function __construct()
    {
//        $this->middleware('auth');
    }

    // Hiển thị trang Dashboard
    public function index(Request $request)
    {
        $user = Auth::user();

        // Lấy keyword và status từ query string
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        // Query đơn nghỉ phép theo user hiện tại + lọc theo keyword & status nếu có
        $leaveRequests = LeaveRequest::with('approver')
            ->where('employee_id', $user->employee_id)
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('type', 'like', "%{$keyword}%")
                      ->orWhere('start_date', 'like', "%{$keyword}%")
                      ->orWhere('end_date', 'like', "%{$keyword}%")
                      ->orWhere('reason', 'like', "%{$keyword}%")
                      ->orWhereHas('approver', function ($subQuery) use ($keyword) {
                          $subQuery->where('name', 'like', "%{$keyword}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(10); // Hiển thị 10 bản ghi mỗi trang

        $managers = User::where('role', 'manager')->with('employee')->get();

        return view('leave.index', compact('leaveRequests', 'managers'));
    }

    // Tạo đơn nghỉ phép mới
    public function create()
    {
        return view('leave.create');
    }

    // Lưu đơn nghỉ phép
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'start_date'    => 'required|date',
            'start_period'  => 'required|in:AM,PM',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'end_period'    => 'required|in:AM,PM',
            'type'          => 'required|string|max:50',
            'reason'        => 'required|string|max:1000',
            'approved_by'   => 'required|exists:users,id',
        ]);

        // Tạo đơn nghỉ phép
        LeaveRequest::create([
            'employee_id'   => $request->employee_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'start_period'  => $request->start_period,
            'end_period'    => $request->end_period,
            'type'       => $request->type, // nếu type là id từ bảng types, đổi tên cho rõ ràng
            'reason'        => $request->reason,
            'status'        => 'pending', // Mặc định trạng thái chờ duyệt
            'submitted_at'  => now(),
            'approved_by'   => $request->approved_by,
        ]);

        return response()->json(['success' => true]);
    }

    // Phê duyệt nghỉ phép
    public function approval(Request $request)
    {
        $user = Auth::user();
        // Lấy keyword và status từ query string
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        // Query đơn nghỉ phép theo user hiện tại + lọc theo keyword & status nếu có
        $leaveRequests = LeaveRequest::with('approver')
            ->where('employee_id', $user->employee_id)
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('type', 'like', "%{$keyword}%")
                      ->orWhere('start_date', 'like', "%{$keyword}%")
                      ->orWhere('end_date', 'like', "%{$keyword}%")
                      ->orWhere('reason', 'like', "%{$keyword}%")
                      ->orWhereHas('employee', function ($q3) use ($keyword) {
                        $q3->where('name', 'like', "%{$keyword}%");
                    });
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(10); // Hiển thị 10 bản ghi mỗi trang


        return view('leave.approval', compact('leaveRequests'));
    }

    public function show($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id); // Tìm đơn nghỉ phép theo ID

        return view('leave.show', compact('leaveRequest')); // Trả về view chi tiết đơn nghỉ phép
    }

    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->delete();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if ($leave->status !== 'pending') {
            return response()->json(['message' => 'Không thể cập nhật trạng thái này'], 403);
        }

        $status = $request->input('status');
        if (!in_array($status, ['approved', 'rejected'])) {
            return response()->json(['message' => 'Trạng thái không hợp lệ'], 422);
        }
        
        if ($status === 'approved') {
            $leaveBlance = LeaveBalance::where('employee_id', $leave->employee_id)
                ->first();
            $totalLeave = $leaveBlance->remaining_days - 1;
            if ($totalLeave <= 0) {
                return response()->json(['message' => 'Ngày phép đã hết'], 422);
            }
            LeaveBalance::where('employee_id', $leave->employee_id)->update([
                'remaining_days' => $totalLeave
            ]);
        }

        $leave->status = $status;
        $leave->approved_by = auth()->user()->employee->id ?? null;
        $leave->save();
        
        return response()->json(['message' => 'Cập nhật trạng thái thành công']);
    }
}
