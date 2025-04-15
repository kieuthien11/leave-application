<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Đăng nhập thành công, chuyển đến trang index
            return redirect()->route('index');
        }

        // Đăng nhập thất bại, quay lại trang login với thông báo lỗi
        return redirect()->route('login')->withErrors(['email' => 'Invalid credentials']);
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
