<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'mahasiswa']);

        $staff = User::query()
            ->where('email', 'staff@example.com')
            ->first();
        if ($staff === null) {
            $staff = User::factory()->create([
                'email' => 'staff@example.com',
                'phone_number' => '081234567891',
            ]);
            $staff->assignRole($staffRole);
        }

        $mahasiswa = User::query()
            ->where('email', 'mahasiswa@example.com')
            ->first();
        if ($mahasiswa === null) {
            $mahasiswa = User::factory()->create([
                'email' => 'mahasiswa@example.com',
                'npm' => '0000000001',
                'phone_number' => '081234567892',
            ]);
            $mahasiswa->assignRole($mahasiswaRole);
        }

        Room::create([
            'name' => 'Ruangan Kelas 1',
            'type' => 'Kelas',
            'status' => 'Tersedia',
        ]);
        Room::create([
            'name' => 'Ruangan Kelas 2',
            'type' => 'Kelas',
            'status' => 'Tidak Tersedia',
        ]);
        Room::create([
            'name' => 'Ruangan Kelas 3',
            'type' => 'Kelas',
            'status' => 'Tersedia',
        ]);
        Room::create([
            'name' => 'Ruangan Lab Komputer',
            'type' => 'Lab',
            'status' => 'Tersedia',
        ]);

    }
}
