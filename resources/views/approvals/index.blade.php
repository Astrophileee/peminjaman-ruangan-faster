@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Daftar Approval</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="ordersTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Ruangan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jam Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($orders as $order)
                    <tr>
                        <td class="whitespace-nowrap text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->room->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->date }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            @foreach ($order->detailOrders as $detail)
                                <div>{{ $detail->time_start }} - {{ $detail->time_end }}</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->note }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button
                                type="button"
                                class="text-blue-600 hover:text-blue-900 border border-blue-600 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                onclick='openEditModal(@json($order))'>
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- Modal Edit -->
<div id="modal-edit-approval" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-approval').classList.add('hidden')"
                class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Approval Peminjaman</h2>

            <form id="editOrderForm" method="POST" action="{{ route('orders.update', ['order' => '__ID__']) }}">
                @csrf
                @method('PATCH')

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="editStatus" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="Accepted">Diterima</option>
                        <option value="Rejected">Ditolak</option>
                    </select>
                </div>

                <!-- Alasan Ditolak (hidden by default) -->
                <div id="rejectedReasonWrapper" class="mt-3 hidden">
                    <label class="block text-sm font-medium text-gray-700">Alasan Penolakan *</label>
                    <textarea name="rejected_reason" id="rejectedReason" rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"
                        placeholder="Masukkan alasan penolakan"></textarea>
                </div>

                <!-- Password -->
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button"
                        onclick="document.getElementById('modal-edit-approval').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@vite(['resources/js/app.js'])
<script>
    const statusSelect = document.getElementById('editStatus');
    const rejectedReasonWrapper = document.getElementById('rejectedReasonWrapper');
    const rejectedReasonInput = document.getElementById('rejectedReason');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'Rejected') {
            rejectedReasonWrapper.classList.remove('hidden');
            rejectedReasonInput.required = true;
        } else {
            rejectedReasonWrapper.classList.add('hidden');
            rejectedReasonInput.required = false;
            rejectedReasonInput.value = '';
        }
    });

    function openEditModal(order) {
        const modal = document.getElementById('modal-edit-approval');
        modal.classList.remove('hidden');

        const form = document.getElementById('editOrderForm');
        form.action = form.action.replace('__ID__', order.id);
        statusSelect.value = '';
        rejectedReasonWrapper.classList.add('hidden');
        rejectedReasonInput.required = false;
        rejectedReasonInput.value = '';
    }
</script>

@if (session('success') || session('error'))
    <div id="flash-message"
        data-type="{{ session('success') ? 'success' : 'error' }}"
        data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif




@endsection
