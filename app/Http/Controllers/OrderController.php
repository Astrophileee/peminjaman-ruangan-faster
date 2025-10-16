<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderMail;
use App\Mail\ApprovalMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['room', 'user', 'detailOrders'])
        ->orderBy('date', 'desc')
        ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required|array|min:1',
            'note' => 'required|string|max:500',
        ]);

        // Ambil data untuk pengecekan bentrok
        $roomId = $request->room_id;
        $date = $request->tanggal;
        $selectedTimes = $request->jam;

        // Cek apakah jam yang dipilih sudah dipakai ruangan lain di tanggal yang sama
        foreach ($selectedTimes as $range) {
            [$start, $end] = explode('-', $range);

            $bentrok = \App\Models\DetailOrder::whereHas('order', function ($q) use ($roomId, $date) {
                    $q->where('room_id', $roomId)
                    ->where('date', $date)
                    ->where('status', '!=', 'Rejected'); // kalau status ditolak masih boleh
                })
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('time_start', [$start, $end])
                    ->orWhereBetween('time_end', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('time_start', '<=', $start)
                            ->where('time_end', '>=', $end);
                    });
                })
                ->exists();

            if ($bentrok) {
                return back()->withErrors([
                    'jam' => "Ruangan sudah dipinjam pada jam $range. Silakan pilih jam lain.",
                ])->withInput();
            }
        }
        $order = Order::create([
            'room_id' => $roomId,
            'user_id' => $request->user_id,
            'date' => $date,
            'note' => $request->note,
            'status' => 'Pending',
            'rejected_reason' => null,
        ]);

         // Ambil semua user dengan role staff
        $staffUsers = User::role('staff')->get();

        // Kirim email ke semua staff
        foreach ($staffUsers as $staff) {
            Mail::to($staff->email)->send(new OrderMail($order));
        }

        foreach ($selectedTimes as $range) {
            [$start, $end] = explode('-', $range);

            DetailOrder::create([
                'order_id' => $order->id,
                'time_start' => $start,
                'time_end' => $end,
            ]);
        }

        return redirect()->route('listRooms.index')->with('success', 'Pengajuan ruangan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    public function approval()
    {
        $orders = Order::with(['room', 'user', 'detailOrders'])
            ->where('status', 'Pending')
            ->orderBy('date', 'desc')
            ->get();

        return view('approvals.index', compact('orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Accepted,Rejected',
            'password' => 'required',
            'rejected_reason' => 'nullable|string|max:255',
        ]);

        // Validasi password staff
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah.')->withInput();
        }

        // Jika ditolak, rejected_reason wajib diisi
        if ($request->status === 'Rejected' && empty($request->rejected_reason)) {
            return back()->with('error', 'Alasan penolakan wajib diisi.')->withInput();
        }

        // Update order
        $order->update([
            'status' => $request->status,
            'rejected_reason' => $request->status === 'Rejected' ? $request->rejected_reason : null,
        ]);

        $mahasiswa = $order->user;
        Mail::to($mahasiswa->email)->send(new ApprovalMail($order));

        return redirect()->route('approvals.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
