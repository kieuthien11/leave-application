@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Phê duyệt nghỉ phép</h2>

    {{-- Tìm kiếm đơn nghỉ phép --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <input type="text" id="searchInput" value="{{ request('keyword') }}" placeholder="Tìm kiếm đơn nghỉ phép..." class="px-4 py-2 border border-gray-300 rounded-lg w-2/3" />
            <select id="statusSelect" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Tất cả trạng thái</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Được phê duyệt</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
            </select>
        </div>
    </div>
    
    <!-- Modal xem chi tiết -->
    <div class="modal fade" id="leaveDetailModal" tabindex="-1" aria-labelledby="leaveDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- modal rộng hơn -->
            <div class="modal-content rounded-xl shadow-lg">
                <div class="modal-header bg-blue-600 text-white rounded-t-xl">
                    <h5 class="modal-title" id="leaveDetailModalLabel">🔍 Chi tiết đơn nghỉ phép</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ngày bắt đầu -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700">Ngày bắt đầu</label>
                            <input type="text" id="modalStartDate" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>

                        <!-- Buổi bắt đầu -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700">Buổi bắt đầu</label>
                            <input type="text" id="modalStartPeriod" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>

                        <!-- Ngày kết thúc -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700">Ngày kết thúc</label>
                            <input type="text" id="modalEndDate" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>

                        <!-- Buổi kết thúc -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700">Buổi kết thúc</label>
                            <input type="text" id="modalEndPeriod" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>

                    <!-- Loại nghỉ -->
                    <div>
                        <label class="block font-semibold text-sm text-gray-700">Loại nghỉ</label>
                        <input type="text" id="modalType" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                    </div>

                    <!-- Người phê duyệt -->
                    <div>
                        <label class="block font-semibold text-sm text-gray-700">Người phê duyệt</label>
                        <input type="text" id="modalApprover" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                    </div>

                    <!-- Trạng thái -->
                    <div>
                        <label class="block font-semibold text-sm text-gray-700">Trạng thái</label>
                        <input type="text" id="modalStatus" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100" readonly>
                    </div>

                    <!-- Lý do -->
                    <div>
                        <label class="block font-semibold text-sm text-gray-700">Lý do</label>
                        <textarea id="modalReason" rows="3" class="w-full mt-1 px-4 py-2 border rounded-lg bg-gray-100 resize-none" readonly></textarea>
                    </div>
                </div>

                <div class="modal-footer px-6 pb-5 pt-3">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bảng danh sách đơn nghỉ phép --}}
    <div class="mx-auto mt-8 bg-white shadow rounded-xl">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-6 py-3 text-left">STT</th> <!-- Thêm dòng này -->
                    <th class="px-6 py-3 text-left">Tên nhân viên</th>
                    <th class="px-6 py-3 text-left">Ngày bắt đầu</th>
                    <th class="px-6 py-3 text-left">Buổi bắt đầu</th>
                    <th class="px-6 py-3 text-left">Ngày kết thúc</th>
                    <th class="px-6 py-3 text-left">Buổi kết thúc</th>
                    <th class="px-6 py-3 text-left">Loại nghỉ</th>
                    <th class="px-6 py-3 text-center">Trạng thái</th>
                    <th class="px-6 py-3 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaveRequests as $index => $leaveRequest)
                    <tr>
                        <td class="px-6 py-4">{{  $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->employee->name }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->start_date }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->start_period == 'AM' ? 'Sáng' : 'Chiều' }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->end_date }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->end_period == 'AM' ? 'Sáng' : 'Chiều' }}</td>
                        <td class="px-6 py-4">{{ $leaveRequest->type }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($leaveRequest->status == 'approved')
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full">Được phê duyệt</span>
                            @elseif($leaveRequest->status == 'pending')
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full">Chờ duyệt</span>
                            @else
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full">Bị từ chối</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button"
                                    class="btn-detail bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded-lg inline-block"
                                    data-bs-toggle="modal" data-bs-target="#leaveDetailModal"
                                    data-start-date="{{ $leaveRequest->start_date }}"
                                    data-start-period="{{ $leaveRequest->start_period }}"
                                    data-end-date="{{ $leaveRequest->end_date }}"
                                    data-end-period="{{ $leaveRequest->end_period }}"
                                    data-type="{{ $leaveRequest->type }}"
                                    data-approver="{{ optional($leaveRequest->approver)->name ?? 'Chưa có' }}"
                                    data-status="{{ $leaveRequest->status }}"
                                    data-reason="{{ $leaveRequest->reason }}">
                                Chi tiết
                            </button>
                            @if(Auth::user()->role == 'manager' && $leaveRequest->status == 'pending')
                            <button type="button"
                                    class="btn-approve bg-green-500 text-white hover:bg-green-800 px-4 py-2 rounded-lg inline-block ml-2"
                                    data-id="{{ $leaveRequest->id }}">
                                Phê duyệt
                            </button>
                            <button type="button"
                                    class="btn-reject bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg inline-block ml-2"
                                    data-id="{{ $leaveRequest->id }}">
                                Từ chối
                            </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-6">
            {{ $leaveRequests->links() }}
        </div>
    </div>
@endsection
@section('script')
    <script>
        const statusLabels = {
            approved: 'Được phê duyệt',
            pending: 'Chờ duyệt',
            rejected: 'Bị từ chối'
        };

        const periodLabels = {
            AM: 'Sáng',
            PM: 'Chiều'
        };

        document.addEventListener('DOMContentLoaded', function () {

            const detailButtons = document.querySelectorAll('.btn-detail');

            detailButtons.forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('modalStartDate').value = button.getAttribute('data-start-date');
                    document.getElementById('modalStartPeriod').value = button.getAttribute('data-start-period') === 'AM' ? 'Sáng' : 'Chiều';
                    document.getElementById('modalEndDate').value = button.getAttribute('data-end-date');
                    document.getElementById('modalEndPeriod').value = button.getAttribute('data-end-period') === 'AM' ? 'Sáng' : 'Chiều';
                    document.getElementById('modalType').value = button.getAttribute('data-type');
                    document.getElementById('modalApprover').value = button.getAttribute('data-approver');
                    document.getElementById('modalStatus').value = convertStatus(button.getAttribute('data-status'));
                    document.getElementById('modalReason').value = button.getAttribute('data-reason');
                });
            });

            function convertStatus(status) {
                switch (status) {
                    case 'approved': return 'Được phê duyệt';
                    case 'pending': return 'Chờ duyệt';
                    case 'rejected': return 'Bị từ chối';
                    default: return status;
                }
            }

            function sendStatusUpdate(id, status) {
                const action = status === 'approved' ? 'phê duyệt' : 'từ chối';
                if (confirm(`Bạn có chắc chắn muốn ${action} đơn nghỉ phép này?`)) {
                    fetch(`/leave/${id}/status`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Có lỗi xảy ra');
                        return res.json();
                    })
                    .then(data => {
                        if (status === 'approved') {
                            alert('Đơn nghỉ phép đã được phê duyệt!');
                        } else {
                            alert('Đơn nghỉ phép đã được từ chối!');
                        }
                        
                        location.reload(); // Reload để cập nhật UI
                    })
                    .catch(err => alert(err.message));
                }
            }

            document.querySelectorAll('.btn-approve').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    sendStatusUpdate(id, 'approved');
                });
            });

            document.querySelectorAll('.btn-reject').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    sendStatusUpdate(id, 'rejected');
                });
            });
            
            const searchInput = document.getElementById('searchInput');
            const statusSelect = document.getElementById('statusSelect');
            let debounceTimeout;

            // Hàm tìm kiếm các đơn nghỉ phép
            function searchLeaveRequests(keyword, status) {
                const url = `/leave/approval?keyword=${encodeURIComponent(keyword)}&status=${encodeURIComponent(status)}`;
                window.location.href = url; // Chuyển hướng trang với từ khóa và trạng thái trên URL
            }

            // Lắng nghe sự kiện nhập liệu vào ô tìm kiếm
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        const keyword = searchInput.value.trim();
                        const status = statusSelect.value;
                        searchLeaveRequests(keyword, status); // Gọi API tìm kiếm đơn nghỉ phép
                    }, 300); // Delay 300ms để debounce
                });
            }

            // Lắng nghe sự kiện thay đổi trạng thái
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    const keyword = searchInput.value.trim();
                    const status = statusSelect.value;
                    searchLeaveRequests(keyword, status); // Gọi API tìm kiếm đơn nghỉ phép
                });
            }

        });
    </script>
@endsection