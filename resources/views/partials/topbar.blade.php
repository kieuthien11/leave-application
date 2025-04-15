<nav class="bg-white shadow-md p-4 flex justify-between items-center w-full fixed top-0 left-0 z-20">
    <div class="text-xl font-bold text-blue-600 pl-4">
        Quản lý Nghỉ Phép
    </div>
    <div class="flex items-center space-x-4 pr-4">
        <div class="text-lg text-gray-700">
            Chào mừng, {{ Auth::user()->name }} 👋
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                🚪 Đăng xuất
            </button>
        </form>
    </div>
</nav>
