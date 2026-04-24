<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\DetailPengembalian;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // ==========================================
    // ADMIN: Kelola Transaksi Peminjaman
    // ==========================================

    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPeminjaman.buku', 'pengembalian.denda']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('detailPeminjaman.buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            })->orWhere('status_pinjaman', 'like', "%{$search}%");
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function cetakPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'detailPeminjaman.buku', 'pengembalian.denda']);

        $filterSearch = $request->search;
        $filterStatus = $request->status;

        if ($filterSearch) {
            $query->whereHas('user', function ($q) use ($filterSearch) {
                $q->where('name', 'like', "%{$filterSearch}%");
            })->orWhereHas('detailPeminjaman.buku', function ($q) use ($filterSearch) {
                $q->where('judul', 'like', "%{$filterSearch}%");
            })->orWhere('status_pinjaman', 'like', "%{$filterSearch}%");
        }

        if ($filterStatus) {
            $query->where('status_pinjaman', $filterStatus);
        }

        $transactions = $query->latest()->get();

        // Summary statistics
        $summary = [
            'total' => $transactions->count(),
            'menunggu' => $transactions->where('status_pinjaman', 'menunggu')->count(),
            'dipinjam' => $transactions->where('status_pinjaman', 'dipinjam')->count(),
            'dikembalikan' => $transactions->where('status_pinjaman', 'dikembalikan')->count(),
            'ditolak' => $transactions->where('status_pinjaman', 'ditolak')->count(),
        ];

        // Total denda
        $totalDenda = $transactions->sum(function ($t) {
            return $t->pengembalian && $t->pengembalian->denda 
                ? $t->pengembalian->denda->jumlah_denda 
                : 0;
        });

        $pdf = Pdf::loadView('admin.transactions.cetak-pdf', compact(
            'transactions', 'summary', 'totalDenda', 'filterSearch', 'filterStatus'
        ));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Transaksi_' . now()->format('Y-m-d_His') . '.pdf');
    }



    // ==========================================
    // ADMIN: Konfirmasi Peminjaman & Pengembalian
    // ==========================================

    public function konfirmasiIndex()
    {
        $pendingPeminjaman = Peminjaman::with(['user', 'detailPeminjaman.buku'])
            ->pending()
            ->latest()
            ->get();

        $pendingPengembalian = Peminjaman::with(['user', 'detailPeminjaman.buku', 'pengembalian'])
            ->pendingReturn()
            ->latest()
            ->get();

        return view('admin.konfirmasi.index', compact('pendingPeminjaman', 'pendingPengembalian'));
    }

    public function approvePeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.buku')->findOrFail($id);

        if ($peminjaman->status_pinjaman !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        // Kurangi stok buku
        foreach ($peminjaman->detailPeminjaman as $detail) {
            if ($detail->buku->stok < $detail->jumlah) {
                return back()->with('error', "Stok buku '{$detail->buku->judul}' tidak mencukupi!");
            }
            $detail->buku->decrement('stok', $detail->jumlah);
        }

        $peminjaman->update(['status_pinjaman' => 'dipinjam']);

        return redirect()->route('admin.konfirmasi.index')
            ->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function rejectPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_pinjaman !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        $peminjaman->update(['status_pinjaman' => 'ditolak']);

        return redirect()->route('admin.konfirmasi.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function approveReturn($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.buku', 'pengembalian'])->findOrFail($id);

        if ($peminjaman->status_pinjaman !== 'menunggu_pengembalian') {
            return back()->with('error', 'Pengembalian ini sudah diproses.');
        }

        $pengembalian = $peminjaman->pengembalian;
        $kondisi = $pengembalian ? $pengembalian->kondisi_buku : 'baik';

        if (!$pengembalian) {
            // Create pengembalian record if it doesn't exist
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tanggal_dikembalikan' => now(),
                'kondisi_buku' => $kondisi,
                'status' => 'selesai',
            ]);
        } else {
            $pengembalian->update(['tanggal_dikembalikan' => now()]);
        }

        // Create detail pengembalian & restore stock
        foreach ($peminjaman->detailPeminjaman as $detail) {
            DetailPengembalian::create([
                'pengembalian_id' => $pengembalian->id,
                'jumlah' => $detail->jumlah,
                'keterangan' => 'Kondisi: ' . $kondisi,
            ]);

            // Jika buku tidak hilang, kembalikan stok
            if ($kondisi !== 'hilang') {
                $detail->buku->increment('stok', $detail->jumlah);
            }
        }

        // Calculate denda
        $lateDays = $peminjaman->lateDays();
        $totalDenda = $lateDays * Denda::DENDA_PER_HARI;

        // Tambahan denda untuk kondisi buku
        if ($kondisi === 'hilang') {
            $totalDenda += 50000; // Denda buku hilang
        } elseif ($kondisi === 'rusak') {
            $totalDenda += 20000; // Denda buku rusak
        }

        if ($totalDenda > 0) {
            Denda::create([
                'pengembalian_id' => $pengembalian->id,
                'jumlah_denda' => $totalDenda,
                'status_bayar' => 'belum_bayar',
            ]);

            $pengembalian->update(['status' => 'denda']);
        }

        $peminjaman->update(['status_pinjaman' => 'dikembalikan']);

        $pesan = 'Pengembalian berhasil dikonfirmasi!';
        if ($totalDenda > 0) {
            $rincian = [];
            if ($lateDays > 0) $rincian[] = "Telat {$lateDays} hari";
            if ($kondisi === 'hilang') $rincian[] = "Buku Hilang";
            if ($kondisi === 'rusak') $rincian[] = "Buku Rusak";
            
            $pesan .= " Terdapat denda (" . implode(', ', $rincian) . "): Rp " . number_format($totalDenda, 0, ',', '.');
        }

        return redirect()->route('admin.konfirmasi.index')
            ->with('success', $pesan);
    }

    // ==========================================
    // ADMIN: Kelola Denda
    // ==========================================

    public function dendaIndex(Request $request)
    {
        $query = Denda::with(['pengembalian.peminjaman.user']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pengembalian.peminjaman.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status_bayar', $request->status);
        }

        $dendas = $query->latest()->paginate(10);

        return view('admin.denda.index', compact('dendas'));
    }

    public function dendaBayar(Denda $denda)
    {
        $denda->update(['status_bayar' => 'dibayar']);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Denda berhasil dibayar!');
    }

    // ==========================================
    // ADMIN: Kelola Kategori
    // ==========================================

    public function kategoriIndex(Request $request)
    {
        $query = \App\Models\Kategori::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kategori', 'like', "%{$request->search}%");
        }

        $kategoris = $query->latest('id_kategori')->paginate(10);

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function kategoriCreate()
    {
        return view('admin.kategori.create');
    }

    public function kategoriStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        \App\Models\Kategori::create($request->only('nama_kategori'));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function kategoriEdit(\App\Models\Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function kategoriUpdate(Request $request, \App\Models\Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
        ]);

        $kategori->update($request->only('nama_kategori'));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function kategoriDestroy(\App\Models\Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    // ==========================================
    // SISWA: Peminjaman Buku
    // ==========================================

    public function pinjamBuku(Request $request)
    {
        $query = Buku::with('kategori')->where('stok', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $books = $query->get();

        return view('siswa.peminjaman', compact('books'));
    }

    public function simpanPeminjaman(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'tanggal_kembali' => 'required|date|after_or_equal:today',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku habis!');
        }

        // Check if user already has pending or active borrow for this book
        $existing = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status_pinjaman', ['menunggu', 'dipinjam'])
            ->whereHas('detailPeminjaman', function ($q) use ($buku) {
                $q->where('buku_id', $buku->id);
            })
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah meminjam atau mengajukan peminjaman buku ini.');
        }

        // Create peminjaman with status MENUNGGU (pending approval)
        $peminjaman = Peminjaman::create([
            'user_id' => auth()->id(),
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'status_pinjaman' => 'menunggu',
        ]);

        // Create detail (stok belum dikurangi sampai admin approve)
        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'buku_id' => $buku->id,
            'jumlah' => 1,
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Peminjaman diajukan! Menunggu persetujuan admin.');
    }

    // ==========================================
    // SISWA: Pengembalian Buku
    // ==========================================

    public function pengembalian()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status_pinjaman', ['dipinjam', 'menunggu_pengembalian'])
            ->with(['detailPeminjaman.buku', 'pengembalian'])
            ->get();

        return view('siswa.pengembalian', compact('peminjamans'));
    }

    public function kembalikanBuku(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'kondisi_buku' => 'required|in:baik,rusak,hilang',
        ]);

        $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
            ->where('user_id', auth()->id())
            ->where('status_pinjaman', 'dipinjam')
            ->with('detailPeminjaman.buku')
            ->firstOrFail();

        // Set status to menunggu_pengembalian (pending admin confirmation)
        $peminjaman->update([
            'status_pinjaman' => 'menunggu_pengembalian',
        ]);

        // Simpan data pengembalian sementara untuk mencatat kondisi buku
        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_dikembalikan' => now(),
            'kondisi_buku' => $request->kondisi_buku,
            'status' => 'selesai',
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Pengembalian diajukan! Menunggu konfirmasi admin.');
    }

    // ==========================================
    // SISWA: Riwayat Peminjaman
    // ==========================================

    public function riwayat()
    {
        $riwayat = Peminjaman::where('user_id', auth()->id())
            ->with(['detailPeminjaman.buku', 'pengembalian.denda'])
            ->latest()
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayat'));
    }
}
