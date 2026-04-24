<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $member = $user->anggota;

        $activeBorrows = Peminjaman::where('user_id', $user->id)
            ->where('status_pinjaman', 'dipinjam')
            ->with(['detailPeminjaman.buku'])
            ->get();

        // Chart Data for current year (12 months)
        $chartData = [];
        $currentYear = now()->year;
        for ($month = 1; $month <= 12; $month++) {
            $count = Peminjaman::where('user_id', $user->id)
                ->whereYear('tanggal_pinjam', $currentYear)
                ->whereMonth('tanggal_pinjam', $month)
                ->count();
            $chartData[] = $count;
        }

        $books = Buku::where('stok', '>', 0)->paginate(10);

        return view('siswa.dashboard', compact('user', 'member', 'activeBorrows', 'chartData', 'books', 'currentYear'));
    }

    public function daftarAnggotaForm()
    {
        if (auth()->user()->anggota) {
            return redirect()->route('siswa.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai anggota.');
        }

        return view('siswa.daftar-anggota');
    }

    public function daftarAnggota(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
        ]);

        Anggota::create([
            'user_id' => auth()->id(),
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Berhasil mendaftar sebagai anggota!');
    }
}
