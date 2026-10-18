<?php

use App\Http\Controllers\LemburController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Halaman depan diarahkan ke Login jika belum login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Route yang HANYA bisa diakses jika sudah Login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        // Cek Role User yang sedang login
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.users'); // Admin diarahkan ke Master User
        }
        // User biasa diarahkan ke Halaman Lembur
        return redirect()->route('lembur.index');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // --- ROUTE LEMBUR ---
    Route::get('/lembur', [LemburController::class, 'index'])->name('lembur.index');
    Route::get('/lembur/tambah', [LemburController::class, 'create'])->name('lembur.create');
    Route::post('/lembur/simpan', [LemburController::class, 'store'])->name('lembur.store');
    Route::get('/lembur/pdf', [LemburController::class, 'downloadPdf'])->name('lembur.pdf');

    // Edit & Hapus Lembur
    Route::get('/lembur/{id}/edit', [LemburController::class, 'edit'])->name('lembur.edit');
    Route::put('/lembur/{id}', [LemburController::class, 'update'])->name('lembur.update');
    Route::delete('/lembur/{id}', [LemburController::class, 'destroy'])->name('lembur.destroy');

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/buat', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/simpan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    });

    // --- ROUTE GUDANG ---
    Route::prefix('gudang')->group(function () {
        Route::get('/', [GudangController::class, 'index'])->name('gudang.index');           // List Stok

        // Transaksi (Masuk/Keluar)
        Route::get('/transaksi', [GudangController::class, 'create'])->name('gudang.create'); // Form Input
        Route::post('/transaksi', [GudangController::class, 'store'])->name('gudang.store');  // Proses Simpan

        // Master Data Barang (CRUD) - WAJIB DI ATAS ROUTE SHOW {id}
        Route::get('/barang/tambah', [GudangController::class, 'createBarang'])->name('gudang.barang.create');
        Route::post('/barang/simpan', [GudangController::class, 'storeBarang'])->name('gudang.barang.store');
        Route::get('/barang/{id}/edit', [GudangController::class, 'editBarang'])->name('gudang.barang.edit');
        Route::put('/barang/{id}', [GudangController::class, 'updateBarang'])->name('gudang.barang.update');
        Route::delete('/barang/{id}', [GudangController::class, 'destroyBarang'])->name('gudang.barang.destroy');
        Route::put('/barang/detail/{detail_id}', [GudangController::class, 'updateSn'])->name('gudang.barang.update_sn');
        Route::delete('/barang/detail/{detail_id}', [GudangController::class, 'deleteSn'])->name('gudang.barang.delete_sn');
        Route::get('/laporan', [GudangController::class, 'laporan'])->name('gudang.laporan');

        // AJAX Get SN (PERBAIKAN 1: Hapus '/gudang' di depannya)
        // Tambahkan parameter opsional {status?}
        Route::get('/get-sn/{id}/{status?}', [GudangController::class, 'getSnByBarang']);

        // Detail/Kartu Stok (PERBAIKAN 2: Pindahkan ke paling bawah agar tidak bentrok)
        Route::get('/{id}', [GudangController::class, 'show'])->name('gudang.show');
    });
});

// Grup Route Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // 1. Menu User
    Route::get('/users', [AdminController::class, 'indexUser'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // 2. Menu Lembur Admin
    Route::get('/lembur', [AdminController::class, 'indexLembur'])->name('admin.lembur');
});

require __DIR__ . '/auth.php';
