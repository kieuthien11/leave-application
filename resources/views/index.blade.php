@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-xl p-6">
        <h1 class="text-2xl font-semibold mb-4 text-gray-800">
            Chào mừng, {{ Auth::user()->name }} 👋
        </h1>

        {{-- Thông tin cá nhân --}}
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-700 mb-2">Thông tin cá nhân</h2>
            <ul class="space-y-1 text-gray-600">
                <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                <li><strong>Vai trò:</strong> {{ ucfirst(Auth::user()->role ?? 'staff') }}</li>
                <li><strong>Ngày tạo tài khoản:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</li>
            </ul>
        </div>

        {{-- Thông tin nghỉ phép (hiển thị cho non-admin) --}}
        @if(Auth::user()->role != 'admin')
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Thông tin nghỉ phép</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-700">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-blue-700">Đã nghỉ</h3>
                        <p class="text-2xl font-bold">
                            {{ $usedLeave ?? 0 }} ngày
                        </p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-green-700">Còn lại</h3>
                        <p class="text-2xl font-bold">
                            {{ $remainingLeave ?? 0 }} ngày
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-yellow-700">Đang chờ duyệt</h3>
                        <p class="text-2xl font-bold">
                            {{ $pendingRequests ?? 0 }} đơn
                        </p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-red-700">Bị từ chối</h3>
                        <p class="text-2xl font-bold">
                            {{ $rejectedRequests ?? 0 }} đơn
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
