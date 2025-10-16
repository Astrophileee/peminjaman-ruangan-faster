<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListRoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('welcome.listRooms', compact('rooms'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('welcome.detailRooms', compact('room'));
    }

    public function checkAvailability($roomId, $date)
    {
        // Ambil semua detail order untuk tanggal dan ruangan yang dipilih
        $orders = Order::where('room_id', $roomId)
            ->where('date', $date)
            ->where('status', '!=', 'Rejected')
            ->with('detailOrders')
            ->get();

        // Kumpulkan semua jam yang bentrok
        $bookedTimes = [];
        foreach ($orders as $order) {
            foreach ($order->detailOrders as $detail) {
                $bookedTimes[] = [
                    'start' => $detail->time_start,
                    'end'   => $detail->time_end,
                ];
            }
        }

        return response()->json($bookedTimes);
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['room', 'detailOrders'])
            ->orderBy('date', 'desc')
            ->get();

        return view('welcome.historyOrders', compact('orders'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
