@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Dashboard</h1>
    </div>

<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Total Ruangan --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-door-open fa-2x text-green-500"></i>
                    <div>
                        <p class="text-gray-500">Total Ruangan</p>
                        <p class="text-2xl font-bold">{{ $totalRooms }}</p>
                    </div>
                </div>

                {{-- Total Mahasiswa --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-user-graduate fa-2x text-blue-500"></i>
                    <div>
                        <p class="text-gray-500">Total Mahasiswa</p>
                        <p class="text-2xl font-bold">{{ $totalMahasiswa }}</p>
                    </div>
                </div>

                {{-- Total Staff --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-user-tie fa-2x text-yellow-500"></i>
                    <div>
                        <p class="text-gray-500">Total Staff</p>
                        <p class="text-2xl font-bold">{{ $totalStaff }}</p>
                    </div>
                </div>

                {{-- Total Accepted --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-check-circle fa-2x text-green-600"></i>
                    <div>
                        <p class="text-gray-500">Total Pengajuan Accepted</p>
                        <p class="text-2xl font-bold">{{ $totalAccepted }}</p>
                    </div>
                </div>

                {{-- Total Pending --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-hourglass-half fa-2x text-orange-500"></i>
                    <div>
                        <p class="text-gray-500">Total Pengajuan Pending</p>
                        <p class="text-2xl font-bold">{{ $totalPending }}</p>
                    </div>
                </div>

                {{-- Total Rejected --}}
                <div class="bg-white p-6 rounded-lg shadow flex items-center space-x-4">
                    <i class="fas fa-times-circle fa-2x text-red-500"></i>
                    <div>
                        <p class="text-gray-500">Total Pengajuan Rejected</p>
                        <p class="text-2xl font-bold">{{ $totalRejected }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


@vite(['resources/js/app.js'])
<script>

</script>

@if (session('success') || session('error'))
    <div id="flash-message"
        data-type="{{ session('success') ? 'success' : 'error' }}"
        data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif




@endsection
