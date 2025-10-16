@extends('layouts.appProfile')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-3 text-xl font-bold text-green-600">
                <img src="/images/logo-pasteur.png" alt="Logo" class="w-14 h-14 object-contain" />
                <span>FasterRoom</span>
            </a>

            {{-- Navigasi kanan --}}
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Register
                    </a>
                @endguest

                @auth
                    <a href="{{ route('listRooms.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-door-open w-5 h-5 pt-1 text-gray-600"></i>Daftar Ruangan
                    </a>
                    <a href="{{ route('listRooms.history') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-clock w-5 h-5 pt-1 text-gray-600"></i>Riwayat Peminjaman
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50 transition">
                            <a href="{{ route('mahasiswa.profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow px-6 py-12 max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                Detail <span class="text-green-600">Ruangan</span>
            </h1>

            <div class="mb-6">
                <p class="text-gray-700"><span class="font-semibold">Nama Ruangan:</span> {{ $room->name }}</p>
                <p class="text-gray-700"><span class="font-semibold">Tipe:</span> {{ $room->type ?? '-' }}</p>
                <p class="text-gray-700"><span class="font-semibold">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $room->status === 'Tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $room->status }}
                    </span>
                </p>
            </div>

            @if(strtolower($room->type) === 'kelas')
                <div class="flex items-start bg-red-50 border border-red-200 text-red-700 text-sm rounded-md p-3 mb-5 max-w-96">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg mr-2 mt-0.5"></i>
                    <span>
                        <strong>Perhatian!</strong> Ruangan ini bertipe <b>kelas</b>.
                        Cek jadwal pembelajaran terlebih dahulu agar tidak bertabrakan.
                        Pengajuan akan <b>ditolak</b> jika jadwal bentrok.
                    </span>
                </div>
            @endif

            @if ($room->status == 'Tersedia')


            <form action="{{ route('listRooms.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                {{-- Tanggal Peminjaman --}}
                <div class="">
                    <label class="block text-gray-700 font-medium mb-2">Pilih Tanggal Peminjaman</label>
                    <input type="date"
                        id="tanggal"
                        name="tanggal"
                        required
                        min="{{ date('Y-m-d') }}"
                        data-room="{{ $room->id }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>



                {{-- Pilihan Jam --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Pilih Jam Peminjaman</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @php
                            $jamList = [
                                '08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00',
                                '11:00 - 12:00', '12:00 - 13:00', '13:00 - 14:00',
                                '14:00 - 15:00'
                            ];
                        @endphp
                        @foreach ($jamList as $jam)
                            <label class="flex items-center space-x-2 border rounded-lg p-2 hover:bg-green-50 cursor-pointer">
                                <input type="checkbox" name="jam[]" value="{{ $jam }}" class="text-green-600">
                                <span class="text-gray-700 text-sm">{{ $jam }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan Pengajuan
                    </label>
                    <textarea id="note" name="note" rows="3"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Contoh: Digunakan untuk kegiatan rapat, presentasi, dsb."></textarea>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Ajukan Peminjaman
                    </button>
                </div>
            </form>
            @else
            <span>
                Ruangan ini <b>Tidak Tersedia</b>. <br>
                <b>Tidak Bisa</b> mengajukan peminjaman ruangan.
            </span>
            @endif
        </div>
    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggal');
    const checkboxes = document.querySelectorAll('input[name="jam[]"]');

    // Awal: semua checkbox disable
    checkboxes.forEach(cb => {
        cb.disabled = true;
        cb.parentElement.classList.add('opacity-50');
    });

    tanggalInput.addEventListener('change', function() {
        const date = this.value;
        const roomId = this.getAttribute('data-room');

        // Kalau belum pilih tanggal, disable semua
        if (!date) {
            checkboxes.forEach(cb => cb.disabled = true);
            return;
        }

        // Aktifkan sementara
        checkboxes.forEach(cb => {
            cb.disabled = false;
            cb.parentElement.classList.remove('opacity-50');
        });

        // Ambil data dari server
        fetch(`{{ url('listRooms/check-availability') }}/${roomId}/${date}`)
            .then(response => response.json())
            .then(data => {
                console.log('✅ Data dari server:', data);
                // data = array of booked times [{start,end}]
                data.forEach(slot => {
                    checkboxes.forEach(cb => {
                        const [start, end] = cb.value.split(' - ');

                        if (slot.start.trim() === start.trim() && slot.end.trim() === end.trim()) {
                            cb.disabled = true;
                            cb.checked = false;
                            cb.parentElement.classList.add('opacity-50');
                        }
                    });
                });
            })
            .catch(err => console.error(err));
    });
});
</script>


@if (session('success') || session('error'))
    <div id="flash-message"
        data-type="{{ session('success') ? 'success' : 'error' }}"
        data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif
@endsection
