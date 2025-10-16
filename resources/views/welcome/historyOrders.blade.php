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

    <main class="flex-grow px-6 py-12 max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Peminjaman</h2>

        @if ($orders->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                <i class="fa-solid fa-info-circle text-blue-500 text-xl mb-2"></i><br>
                Belum ada riwayat peminjaman ruangan.
            </div>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $order->room->name }}
                            </h3>

                            @php
                                $statusColor = match($order->status) {
                                    'Accepted' => 'bg-green-100 text-green-700',
                                    'Pending' => 'bg-yellow-100 text-yellow-700',
                                    'Rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp


                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <p class="text-gray-600 mb-1">
                            <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->date)->translatedFormat('d F Y') }}
                        </p>

                        <p class="text-gray-600 mb-1">
                            <strong>Jam:</strong>
                            @foreach ($order->detailOrders as $detail)
                                {{ $detail->time_start }} - {{ $detail->time_end }}@if (!$loop->last), @endif
                            @endforeach
                        </p>

                        @if ($order->note)
                            <p class="text-gray-600 italic mb-2">
                                <strong>Catatan:</strong> {{ $order->note }}
                            </p>
                        @endif

                        @if ($order->status === 'Rejected' && $order->rejected_reason)
                            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded-md text-red-700 text-sm">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                                <strong>Alasan Ditolak:</strong> {{ $order->rejected_reason }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
