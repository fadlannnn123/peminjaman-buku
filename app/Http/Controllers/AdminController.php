<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBooks = Buku::count();
        $totalMembers = Anggota::count();
        $totalTransactions = Peminjaman::count();
        $activeBorrows = Peminjaman::where('status_pinjaman', 'dipinjam')->count();
        $totalDenda = Denda::where('status_bayar', 'belum_bayar')->sum('jumlah_denda');

        // Pending counts
        $pendingPeminjaman = Peminjaman::pending()->count();
        $pendingPengembalian = Peminjaman::pendingReturn()->count();

        $recentTransactions = Peminjaman::with(['user', 'detailPeminjaman.buku'])
            ->latest()
            ->take(5)
            ->get();

        // Data Grafik Batang: Peminjaman & Pengembalian per Bulan dalam Tahun Ini
        $chartDataPeminjaman = [];
        $chartDataPengembalian = [];
        $currentYear = now()->year;
        
        for ($month = 1; $month <= 12; $month++) {
            $chartDataPeminjaman[] = Peminjaman::whereYear('tanggal_pinjam', $currentYear)
                ->whereMonth('tanggal_pinjam', $month)
                ->count();
                
            $chartDataPengembalian[] = \App\Models\Pengembalian::whereYear('tanggal_dikembalikan', $currentYear)
                ->whereMonth('tanggal_dikembalikan', $month)
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalMembers',
            'totalTransactions',
            'activeBorrows',
            'totalDenda',
            'pendingPeminjaman',
            'pendingPengembalian',
            'recentTransactions',
            'chartDataPeminjaman',
            'chartDataPengembalian'
        ));
    }
}
