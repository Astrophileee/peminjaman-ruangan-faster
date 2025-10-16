<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();

        return view('rooms.index', compact('rooms'));
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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $room = Room::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'status' => $validated['status'],
            ]);
            DB::commit();
            return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data ruangan.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
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
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $room) {
            $data = $request->only(['name', 'type', 'status']);

            $room->update($data);
        });

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('rooms.index')->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
