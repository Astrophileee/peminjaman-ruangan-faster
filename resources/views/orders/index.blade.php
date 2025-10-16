@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Daftar Peminjaman</h1>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($orders as $order)
                @php
                    $status = strtolower($order->status);
                    $badge = match($status) {
                        'rejected'  => ['label' => 'Rejected', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                        'pending'  => ['label' => 'Pending', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                        'accepted' => ['label' => 'Accepted', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                        default   => ['label' => ucfirst($order->status), 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                    };
                @endphp
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
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
