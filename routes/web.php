<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rute khusus untuk pengelolaan kegiatan
Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan/buat', function () {
        return view('pages.pengajuan.buat');
    })->name('pengajuan.buat');

    Route::get('/pengajuan/riwayat', function () {
        return view('pages.pengajuan.riwayat');
    })->name('pengajuan.riwayat');

    // Rute khusus Kaprodi
    Route::get('/approval', function () {
        return view('pages.approval.index');
    })->name('approval.index');

    // Rute khusus TU
    Route::get('/penomoran-surat', function () {
        return view('pages.tu.index');
    })->name('tu.index');

    // Rute Edit/Revisi Kegiatan
    Route::get('/pengajuan/{id}/revisi', \App\Livewire\FormRevisiKegiatan::class)->name('pengajuan.revisi');

    Route::get('/pengajuan/{id}/detail', \App\Livewire\DetailKegiatanDosen::class)->name('pengajuan.detail');

    Route::get('/notifikasi', \App\Livewire\HalamanNotifikasi::class)->name('notifikasi.index');

    Route::get('/approval/{id}/detail', \App\Livewire\DetailApprovalKaprodi::class)->name('approval.detail');
});

require __DIR__.'/auth.php';
