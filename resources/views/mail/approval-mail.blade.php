@component('mail::message')
# Status Peminjaman Ruangan Kamu

Halo, {{ $order->user->name }}!

Pengajuan kamu untuk ruangan telah di-update.

**Ruangan:** {{ $order->room->name }}
**Tanggal:** {{ $order->date }}
**Catatan:** {{ $order->note }}

**Status:**
@switch($order->status)
    @case('Accepted')
        **Diterima**
        @break
    @case('Rejected')
        **Ditolak**
        @break
    @default
        {{ $order->status }}
@endswitch

@if($order->status === 'Rejected' && $order->rejected_reason)
**Alasan Ditolak:** {{ $order->rejected_reason }}
@endif

@component('mail::button', ['url' => route('listRooms.history')])
Lihat Pengajuan
@endcomponent

Terima kasih, 
{{ config('app.name') }}
@endcomponent
