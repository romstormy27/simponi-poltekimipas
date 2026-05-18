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
            'Dosen Biasa',
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

        // 2. Membuat Akun Super Admin Utama
        $admin = User::create([
            'name' => 'Super Admin Simponi',
            'email' => 'admin@poltekimipas.ac.id',
            'password' => Hash::make('ImipasPrima2026!'),
        ]);

        // 3. Pasangkan Role 'Super Admin' ke Akun Tersebut
        $admin->assignRole('Super Admin');

        // Membuat Akun Dosen untuk Uji Coba Form
        $dosen1 = User::create([
            'name' => 'Muhammad Fahrury Romdendine, S.Kom., M.Kom.',
            'email' => 'romdendine@poltekim.ac.id',
            'password' => Hash::make('ImipasPrima2026!'),
        ]);
        
        $dosen1->assignRole('Dosen Biasa');

        // Membuat Akun Dosen tambahan untuk Uji Coba Form
        $dosen2 = User::create([
            'name' => 'Okky Pratama Martadireja, S.T., M.M.',
            'email' => 'okky@poltekim.ac.id',
            'password' => Hash::make('ImipasPrima2026!'),
        ]);
        
        $dosen2->assignRole('Dosen Biasa');

        // Membuat Akun Kaprodi untuk Uji Coba Approval
        $kaprodi = User::create([
            'name' => 'Wilonotomo,S.Kom., M.Si.',
            'email' => 'kaprodi.mantek@poltekimipas.ac.id',
            'password' => Hash::make('PasswordKaprodi123!'),
        ]);
        
        $kaprodi->assignRole('Ketua Program Studi');

        // Membuat Akun Tata Usaha (TU)
        $tu = User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'tu@poltekimipas.ac.id',
            'password' => Hash::make('PasswordTU123!'),
        ]);
        
        $tu->assignRole('Kepala Sub Bagian TU');
    }
}