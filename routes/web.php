<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ============================================
// ROOT (FIX REDIRECT LOOP)
// ============================================
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == 'siswa') {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
});


// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Buku
    Route::resource('books', BookController::class)->names('admin.books');

    // Anggota
    Route::resource('members', MemberController::class)->names('admin.members');

    // Transaksi
    Route::get('transactions/cetak-pdf', [TransactionController::class, 'cetakPdf'])->name('admin.transactions.cetak-pdf');
    Route::get('transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');

    // Konfirmasi Peminjaman & Pengembalian
    Route::get('/konfirmasi', [TransactionController::class, 'konfirmasiIndex'])->name('admin.konfirmasi.index');
    Route::post('/konfirmasi/{id}/approve', [TransactionController::class, 'approvePeminjaman'])->name('admin.konfirmasi.approve');
    Route::post('/konfirmasi/{id}/reject', [TransactionController::class, 'rejectPeminjaman'])->name('admin.konfirmasi.reject');
    Route::post('/konfirmasi/{id}/approve-return', [TransactionController::class, 'approveReturn'])->name('admin.konfirmasi.approve-return');

    // Kategori
    Route::get('/kategori', [TransactionController::class, 'kategoriIndex'])->name('admin.kategori.index');
    Route::get('/kategori/cetak', [TransactionController::class, 'kategoriCetak'])->name('admin.kategori.cetak');
    Route::get('/kategori/create', [TransactionController::class, 'kategoriCreate'])->name('admin.kategori.create');
    Route::post('/kategori', [TransactionController::class, 'kategoriStore'])->name('admin.kategori.store');
    Route::get('/kategori/{kategori}/edit', [TransactionController::class, 'kategoriEdit'])->name('admin.kategori.edit');
    Route::put('/kategori/{kategori}', [TransactionController::class, 'kategoriUpdate'])->name('admin.kategori.update');
    Route::delete('/kategori/{kategori}', [TransactionController::class, 'kategoriDestroy'])->name('admin.kategori.destroy');

    // Denda
    Route::get('/denda', [TransactionController::class, 'dendaIndex'])->name('admin.denda.index');
    Route::post('/denda/{denda}/bayar', [TransactionController::class, 'dendaBayar'])->name('admin.denda.bayar');
});


// ============================================
// SISWA ROUTES
// ============================================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('siswa.dashboard');

    Route::get('/daftar-anggota', [StudentController::class, 'daftarAnggotaForm'])->name('siswa.daftar-anggota');
    Route::post('/daftar-anggota', [StudentController::class, 'daftarAnggota'])->name('siswa.daftar-anggota.store');

    Route::get('/peminjaman', [TransactionController::class, 'pinjamBuku'])->name('siswa.peminjaman');
    Route::post('/peminjaman', [TransactionController::class, 'simpanPeminjaman'])->name('siswa.peminjaman.store');

    Route::get('/pengembalian', [TransactionController::class, 'pengembalian'])->name('siswa.pengembalian');
    Route::post('/pengembalian', [TransactionController::class, 'kembalikanBuku'])->name('siswa.pengembalian.store');

    Route::get('/riwayat', [TransactionController::class, 'riwayat'])->name('siswa.riwayat');
});


// ============================================
// AUTH
// ============================================
require __DIR__.'/auth.php';