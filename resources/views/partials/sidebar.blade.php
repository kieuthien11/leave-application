<aside class="w-64 h-screen bg-white shadow-md fixed top-0 left-0 z-10 pt-16">
    <nav class="flex flex-col p-4 space-y-3 text-gray-800">
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('user.index') }}" class="hover:text-blue-600">👤 Quản lý người dùng</a>
        @endif

        @if(in_array(Auth::user()->role, ['manager', 'staff']))
            <a href="{{ route('index') }}" class="hover:text-blue-600">📊 Dashboard</a>
            <a href="{{ route('leave.index') }}" class="hover:text-blue-600">📝 Nghỉ phép</a>
        @endif

        @if(Auth::user()->role === 'manager')
            <a href="{{ route('leave.approval') }}" class="hover:text-blue-600">✅ Phê duyệt nghỉ phép</a>
        @endif
    </nav>
</aside>
