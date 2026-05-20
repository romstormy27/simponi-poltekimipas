<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    public function run(): void // <--- Pastikan di sini tulisannya "run", bukan "main"
    {
        // 1. Membuat Daftar Peran (Roles) Sesuai Kebutuhan Kampus
        $roles = [
            'Super Admin',
            'Dosen Program Studi',
            'Ketua Program Studi',
            'Direktur',
            'Wakil Direktur Bid. Akademik',
            'Kepala SPMI',
            'Kepala P3M',
            'Kepala Sub Bagian TU'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        // 1. Akun Superadmin (Bisa pakai username ringkas)
        $admin = \App\Models\User::create([
            'name' => 'Super Administrator',
            'username' => 'admin_simponi',
            'email' => 'admin@poltekimipas.ac.id',
            'password' => bcrypt('password'), // Password default: password
        ]);
        $admin->assignRole('Super Admin'); // Sesuaikan nama role Spatie Anda

        // 2. Akun Tata Usaha (TU)
        $tu = \App\Models\User::create([
            'name' => 'Kepala Sub Bagian Tata Usaha',
            'username' => 'admin_tu',
            'email' => 'tu@poltekimipas.ac.id',
            'password' => bcrypt('password'),
        ]);
        $tu->assignRole('Kepala Sub Bagian TU');

        // 3. Akun Kaprodi 
        $kaprodi = \App\Models\User::create([
            'name' => 'Wilonotomo, S.Kom., M.Si.',
            'username' => '19912272024041001', // NIP sebagai Username
            'email' => 'kaprodi.mtk@poltekimipas.ac.id',
            'program_studi' => 'Manajemen Teknologi Keimigrasian', // Wajib diisi untuk filter
            'password' => bcrypt('password'),
        ]);
        $kaprodi->assignRole('Ketua Program Studi');

        // 4. Akun Dosen Pengusul (Ketua)
        $dosen1 = \App\Models\User::create([
            'name' => 'Dosen Pengusul, S.Kom., M.Kom.',
            'username' => '199912272024041002', // NIP sebagai Username
            'email' => 'dosen1@poltekimipas.ac.id',
            'program_studi' => 'Manajemen Teknologi Keimigrasian', // Harus SAMA dengan Kaprodi
            'password' => bcrypt('password'),
        ]);
        $dosen1->assignRole('Dosen Program Studi');

        // 5. Akun Dosen Anggota (Lintas Prodi / Sama Prodi)
        $dosen2 = \App\Models\User::create([
            'name' => 'Dosen Anggota, S.H., M.H.',
            'username' => '199511152024041003', // NIP sebagai Username
            'email' => 'dosen2@poltekimipas.ac.id',
            'program_studi' => 'Hukum Keimigrasian', // Beda prodi tidak masalah untuk anggota
            'password' => bcrypt('password'),
        ]);
        $dosen2->assignRole('Dosen Program Studi');
    }
}