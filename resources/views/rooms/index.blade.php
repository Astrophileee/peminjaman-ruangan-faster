@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Daftar Ruangan</h1>
        <button onclick="document.getElementById('modal-tambah-room').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
            Tambah
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="roomsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($rooms as $room)
                @php
                    $status = strtolower($room->status);
                    $badge = match($status) {
                        'tidak tersedia'  => ['label' => 'Tidak Tersedia', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                        'tersedia' => ['label' => 'Tersedia', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                        default   => ['label' => ucfirst($room->status), 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                    };
                @endphp
                    <tr>
                        <td class="whitespace-nowrap text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $room->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $room->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Tombol Edit -->
                                <button
                                    type="button"
                                    class="text-blue-600 hover:text-blue-900 border border-blue-600 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    onclick='openEditModal(@json($room))'>
                                    Edit
                                </button>

                                <!-- Tombol Delete -->
                                <form id="deleteForm{{ $room->id }}" action="{{ route('rooms.destroy', $room) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        onclick="confirmDelete('{{ $room->id }}')"
                                        class="text-red-600 hover:text-red-900 border border-red-600 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- Modal Tambah -->
<div id="modal-tambah-room" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <!-- Close button -->
            <button onclick="document.getElementById('modal-tambah-room').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah Ruangan</h2>

                <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe * </label>
                        <input type="text" name="type" value="{{ old('type') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('type')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Tidak Tersedia" {{ old('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-room').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                    </div>
                </form>

        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-room" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-room').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Ruangan</h2>

            <form id="editRoomForm" method="POST" enctype="multipart/form-data"
            action="{{ route('rooms.update', ['room' => '__ID__']) }}">
                @csrf
                @method('PATCH')

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama *</label>
                    <input type="text" name="name" id="editName" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                    @error('name')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Npm -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe *</label>
                    <input type="text" name="type" id="editType" value="{{ old('type') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                    @error('type')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="editStatus" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Tidak Tersedia" {{ old('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                    @error('status')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-room').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@vite(['resources/js/app.js'])
<script>
    function openEditModal(room) {
        const modal = document.getElementById('modal-edit-room');
        modal.classList.remove('hidden');

        const form = document.getElementById('editRoomForm');
        form.action = form.action.replace('__ID__', room.id);

        document.getElementById('editName').value = room.name ?? '';
        document.getElementById('editType').value = room.type ?? '';
        document.getElementById('editStatus').value = room.status ?? '';
    }

function confirmDelete(roomId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + roomId).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-room').classList.remove('hidden');
    @endif
});

function resetForm() {
    const form = document.querySelector('#modal-tambah-room form');
    form.reset();
}

document.querySelector('#modal-tambah-room .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-room').classList.add('hidden');
});



</script>

@if (session('success') || session('error'))
    <div id="flash-message"
        data-type="{{ session('success') ? 'success' : 'error' }}"
        data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editRoom'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editRoom')));
        }
    </script>
@endif



@endsection
