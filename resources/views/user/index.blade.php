@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Danh sách người dùng</h2>

    {{-- Nút Tạo người dùng mới --}}
    <div class="mb-6 text-right">
        <button type="button"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow inline-block"
                data-bs-toggle="modal" data-bs-target="#createUserModal">
            + Tạo người dùng mới
        </button>
    </div>

    {{-- Search and Filter --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4 w-full">
            <input
                type="text"
                placeholder="Tìm kiếm user..."
                class="px-4 py-2 border border-gray-300 rounded-lg w-1/3"
                id="searchInput"
                value="{{ request('keyword') }}"
            />
        </div>
    </div>

    {{-- Modal Tạo người dùng --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Tạo người dùng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Tên người dùng</label>
                            <input type="text" name="name" id="name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Mật khẩu</label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-semibold text-gray-700">Vai trò</label>
                            <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                    required>
                                <option value="admin">Quản trị viên</option>
                                <option value="manager">Quản lý</option>
                                <option value="staff">Nhân viên</option>
                            </select>
                        </div>

                        {{-- Các trường thêm cho manager và employee --}}
                        <div id="extraFields" style="display: none;">
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-semibold text-gray-700">Số điện
                                    thoại</label>
                                <input type="text" name="phone" id="phone"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label for="department" class="block text-sm font-semibold text-gray-700">Phòng
                                    ban</label>
                                <input type="text" name="department" id="department"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label for="position" class="block text-sm font-semibold text-gray-700">Chức vụ</label>
                                <input type="text" name="position" id="position"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label for="hire_date" class="block text-sm font-semibold text-gray-700">Ngày vào
                                    làm</label>
                                <input type="date" name="hire_date" id="hire_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg shadow"
                                data-bs-dismiss="modal">
                            Hủy
                        </button>
                        <button type="button" id="createUserButton"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow">
                            Tạo người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="editName">Tên người dùng</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label for="editEmail">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" disabled>
                        </div>
                        <div class="mb-4">
                            <label for="editPassword">Mật khẩu (để trống nếu không đổi)</label>
                            <input type="password" name="password" id="editPassword" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label for="editRole">Vai trò</label>
                            <select name="role" id="editRole" class="form-control" required>
                                <option value="admin">Quản trị viên</option>
                                <option value="manager">Quản lý</option>
                                <option value="staff">Nhân viên</option>
                            </select>
                        </div>
                        <div id="employeeFields" style="display: none;">
                            <div class="mb-4">
                                <label for="editPhone">Số điện thoại</label>
                                <input type="text" name="phone" id="editPhone" class="form-control">
                            </div>
                            <div class="mb-4">
                                <label for="editDepartment">Phòng ban</label>
                                <input type="text" name="department" id="editDepartment" class="form-control">
                            </div>
                            <div class="mb-4">
                                <label for="editPosition">Chức vụ</label>
                                <input type="text" name="position" id="editPosition" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg shadow"
                                data-bs-dismiss="modal">
                            Hủy
                        </button>
                        <button type="submit" id="submitEditBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bảng danh sách người dùng --}}
    <div class="mx-auto mt-8 bg-white shadow rounded-xl">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
            <tr class="bg-gray-200">
                <th class="px-6 py-3 text-left">STT</th> <!-- Thêm dòng này -->
                <th class="px-6 py-3 text-left">Tên người dùng</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-left">Vai trò</th>
                <th class="px-6 py-3 text-left">Phòng ban</th>
                <th class="px-6 py-3 text-left">Chức vụ</th>
                <th class="px-6 py-3 text-left">Số điện thoại</th>
                <th class="px-6 py-3 text-center">Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td class="px-6 py-4">{{  $index + 1 }}</td>
                    <!-- Hiển thị số thứ tự đúng theo trang -->
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                    <td class="px-6 py-4">{{ $user->employee->department ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $user->employee->position ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $user->employee->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <button type="button"
                            class="btn-edit-user bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded-lg inline-block"
                            data-user='@json($user)'>
                            Sửa
                        </button>
                        <button type="button"
                             class="btn-delete-user bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg inline-block ml-2"
                             data-id="{{ $user->id }}"
                           data-name="{{ $user->name }}">
                           Xóa
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-6 flex justify-center items-center">
            <div class="bg-white p-4 rounded-lg shadow">
                {!! $users->appends(request()->query())->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const extraFields = document.getElementById('extraFields');

        roleSelect.addEventListener('change', function () {
            const selectedRole = this.value;
            if (selectedRole === 'manager' || selectedRole === 'staff') {
                extraFields.style.display = 'block';
            } else {
                extraFields.style.display = 'none';
            }
        });

        // Submit Ajax
        document.getElementById('createUserButton').addEventListener('click', function () {
            const button = this;
            button.disabled = true; // Disable nút khi click
            button.innerText = 'Đang lưu...';

            const form = document.getElementById('createUserForm');
            const formData = new FormData(form);

            fetch('/user', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                        modal.hide();
                        alert('Người dùng đã được tạo thành công');
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                        button.disabled = false; // Bật lại nút nếu có lỗi từ server
                        button.innerText = 'Tạo người dùng';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    button.disabled = false; // Bật lại nút nếu fetch bị lỗi
                    button.innerText = 'Tạo người dùng';
                });
        });

        // Lắng nghe tất cả nút "Sửa"
        document.querySelectorAll('.btn-edit-user').forEach(button => {
            button.addEventListener('click', function () {
                const user = JSON.parse(this.getAttribute('data-user'));
                const employee = user.employee ?? {};

                // Gán dữ liệu vào form
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editName').value = user.name;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editRole').value = user.role;

                // Nếu không phải admin thì hiển thị thông tin employee
                if (user.role !== 'admin') {
                    document.getElementById('employeeFields').style.display = 'block';
                    document.getElementById('editPhone').value = employee.phone ?? '';
                    document.getElementById('editDepartment').value = employee.department ?? '';
                    document.getElementById('editPosition').value = employee.position ?? '';
                } else {
                    document.getElementById('employeeFields').style.display = 'none';
                }

                // Hiển thị modal
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            });
        });

        // Gửi request cập nhật khi submit
        document.getElementById('editUserForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitEditBtn');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Đang lưu...';
            const userId = document.getElementById('editUserId').value;
            const formData = new FormData(this);

            fetch(`/user/${userId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cập nhật thành công!');
                        location.reload();
                    } else {
                        alert('Đã xảy ra lỗi!');
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Lưu thay đổi';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi hệ thống!');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Lưu thay đổi';
                });
        });

        document.querySelectorAll('.btn-delete-user').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');

                if (confirm(`Bạn có chắc muốn xóa người dùng "${userName}" không?`)) {
                    fetch(`/user/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Xóa người dùng thành công!');
                                location.reload();
                            } else {
                                alert('Đã có lỗi xảy ra khi xóa.');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Đã có lỗi xảy ra.');
                        });
                }
            });
        });

        // Search input + debounce
        const searchInput = document.getElementById('searchInput');
        let debounceTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    const keyword = searchInput.value.trim();
                    searchUsers(keyword);
                }, 300);
            });
        }

        function searchUsers(keyword) {
            const url = `/user?keyword=${encodeURIComponent(keyword)}`;
            window.location.href = url; // Reload trang với keyword trên URL
        }
    });
</script>
@endsection
