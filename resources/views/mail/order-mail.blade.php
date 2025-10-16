@component('mail::message')
# Pengajuan Peminjaman Ruangan Baru

Halo, ada pengajuan peminjaman ruangan baru.

**Nama Peminjam:** {{ $order->user->name }}
**Ruangan:** {{ $order->room->name }}
**Tanggal:** {{ $order->date }}
**Catatan:** {{ $order->note }}

@component('mail::button', ['url' => route('orders.index')])
Lihat Pengajuan
@endcomponent

Terima kasih, 
{{ config('app.name') }}
@endcomponent
