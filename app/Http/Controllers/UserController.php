<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Constructor để đảm bảo chỉ người dùng có quyền mới truy cập
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('admin'); // Chỉ admin mới có thể truy cập
    }

    // Hiển thị danh sách người dùng
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $users = User::query()->with('employee')
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            })
            ->paginate(10);
        return view('user.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('user.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,manager,staff',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'position' => 'nullable|string',
            'hire_date' => 'nullable|date',
        ]);

        $data['password'] = Hash::make($data['password']);
        $employeeId = null;
        if (in_array($data['role'], ['staff', 'manager'])) {
            // Tạo nhân viên
            $employee = Employee::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'department' => $data['department'],
                'position' => $data['position'],
                'hire_date' => $data['hire_date'] ?? now()->toDateString(),
            ]);
            $employeeId = $employee->id;

            LeaveBalance::create([
                'employee_id' => $employeeId,
                'type' => 'Annual Leave', // Dùng loại nghỉ phép Annual
                'year' => now()->year, // Gán năm hiện tại
                'remaining_days' => 0, // Số ngày nghỉ còn lại cho loại Annual
            ]);
        }
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'employee_id' => $employeeId
        ]);

        return response()->json(['success' => true]);
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,manager,staff',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Nếu không phải admin thì cập nhật hoặc tạo employee
        if (in_array($user->role, ['manager', 'staff'])) {
            $employeeData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'department' => $request->department,
                'position' => $request->position,
            ];

            // Nếu user đã có employee thì update, chưa có thì tạo mới
            if ($user->employee) {
                $user->employee->update($employeeData);
            }
        }

        return response()->json(['success' => true]);
    }


    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Xóa employee nếu có liên kết
        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        $users = User::with('employee')
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->limit(20)
            ->get();

        return response()->json(['users' => $users]);
    }

}
