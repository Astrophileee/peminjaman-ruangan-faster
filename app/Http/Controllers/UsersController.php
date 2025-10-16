<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use libphonenumber\NumberParseException;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::pluck('name');
        $users = User::all();

        return view('users.index', compact('roles','users'));
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
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric|phone:ID|unique:users,phone_number',
            'npm' => [
            Rule::requiredIf($request->role === 'mahasiswa'),
                'nullable',
                'numeric',
                'unique:users,npm'
            ],
        ]);

        DB::beginTransaction();

        try {
            try {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone_number'],
                    'npm' => $validated['npm'] ?? null,
                    'password' => Hash::make('password'),
                ]);
            } catch (NumberParseException $e) {
                return back()->withErrors(['phone_number' => 'Nomor telepon tidak valid.'])->withInput();
            }
            $user->assignRole($validated['role']);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data pengguna.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('welcome.profile', compact('user'));
    }

    public function updateMahasiswa(Request $request)
    {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npm' => [
                'required',
                'numeric',
                Rule::unique('users', 'npm')->ignore($user->id, 'id'),
            ],
            'phone_number' => [
                'required',
                'numeric',
                'phone:ID',
                Rule::unique('users', 'phone_number')->ignore($user->id, 'id'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id, 'id'),
            ],
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update data user
        $user->name = $validated['name'];
        $user->npm = $validated['npm'];
        $user->phone_number = $validated['phone_number'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id, 'id'),
            ],
            'phone_number' => [
                'required',
                'numeric',
                'phone:ID',
                Rule::unique('users', 'phone_number')->ignore($user->id, 'id'),
            ],
            'role' => 'required|string',
            'password' => 'nullable|string',
            'npm' => [
                Rule::requiredIf($request->role === 'mahasiswa'),
                'nullable',
                'numeric',
                Rule::unique('users', 'npm')->ignore($user->id, 'id'),
            ],
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = $request->only(['name', 'email', 'phone_number', 'npm']);
            if ($request->role !== 'mahasiswa') {
                $data['npm'] = null;
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->syncRoles([$request->role]);
        });

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('users.index')->with('error', 'User tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
