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

    <main class="flex-grow flex flex-col justify-center items-center text-center px-6 py-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Selamat Datang di <span class="text-green-600">FasterRoom</span>
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mb-6">
            Sistem manajemen ruangan bagi mahasiswa yang ingin meminjam ruangan di Fakultas Sains Terapan.
            Atur jadwal dan lihat ketersediaan ruangan.
        </p>
    </main>
</div>
@endsection
