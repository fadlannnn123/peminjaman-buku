@extends('layouts.admin')
@section('title', 'Data Transaksi')
@section('subtitle', 'Manajemen peminjaman dan pengembalian buku')

@section('content')
    <div class="rounded-2xl overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau buku..."
                    class="px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;">
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors" style="background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors" style="background: rgba(255,255,255,0.05); color: #9ca3af;">Reset</a>
                @endif
            </form>
            <div class="flex items-center gap-2">
                {{-- Cetak PDF with filter --}}
                <div class="relative" id="cetakPdfDropdown">
                    <button type="button" onclick="toggleCetakDropdown()" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl transition-all shadow-sm gap-2 hover:scale-[1.02]" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: white; border: 1px solid rgba(239,68,68,0.3);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Cetak Laporan PDF
                        <svg class="w-3 h-3 ml-1 transition-transform" id="cetakChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="cetakMenu" class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-2xl z-50 overflow-hidden" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
                        <div class="px-4 py-2.5" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Filter Status Laporan</p>
                        </div>
                        <a href="{{ route('admin.transactions.cetak-pdf', ['search' => request('search')]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            Semua Transaksi
                        </a>
                        <a href="{{ route('admin.transactions.cetak-pdf', ['status' => 'menunggu', 'search' => request('search')]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-amber-500/20 hover:text-amber-300 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Menunggu
                        </a>
                        <a href="{{ route('admin.transactions.cetak-pdf', ['status' => 'dipinjam', 'search' => request('search')]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-blue-500/20 hover:text-blue-300 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            Dipinjam
                        </a>
                        <a href="{{ route('admin.transactions.cetak-pdf', ['status' => 'dikembalikan', 'search' => request('search')]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-emerald-500/20 hover:text-emerald-300 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Dikembalikan
                        </a>
                        <a href="{{ route('admin.transactions.cetak-pdf', ['status' => 'ditolak', 'search' => request('search')]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/20 hover:text-red-300 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                            Ditolak
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Buku (Jumlah)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl Pinjam/Kembali</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>

                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($transactions as $i => $transaction)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $transactions->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-200">{{ $transaction->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <ul class="list-disc pl-4">
                                    @foreach($transaction->detailPeminjaman as $detail)
                                        <li>{{ $detail->buku->judul }} <span class="text-xs font-semibold px-1.5 py-0.5 rounded-md" style="background: rgba(255,255,255,0.1);">{{ $detail->jumlah }}</span></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <div class="text-emerald-400 mb-1">Pinjam: {{ $transaction->tanggal_pinjam->format('d M Y') }}</div>
                                @if($transaction->tanggal_kembali)
                                    <div class="text-amber-400">Kembali: {{ $transaction->tanggal_kembali->format('d M Y') }}</div>
                                @else
                                    <div class="text-gray-500 italic">Belum dikembalikan</div>
                                @endif
                                @if($transaction->pengembalian && $transaction->pengembalian->denda)
                                    <div class="mt-1 text-xs font-semibold text-red-400">
                                        Denda: Rp {{ number_format($transaction->pengembalian->denda->jumlah_denda, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($transaction->status_pinjaman === 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(245,158,11,0.15); color: #fbbf24;">Menunggu</span>
                                @elseif($transaction->status_pinjaman === 'dipinjam')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(59,130,246,0.15); color: #60a5fa;">Dipinjam</span>
                                @elseif($transaction->status_pinjaman === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(239,68,68,0.15); color: #f87171;">Ditolak</span>
                                @elseif($transaction->status_pinjaman === 'menunggu_pengembalian')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(139,92,246,0.15); color: #a78bfa;">Menunggu Kembali</span>
                                @elseif($transaction->status_pinjaman === 'terlambat')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(239,68,68,0.15); color: #f87171;">Terlambat</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(16,185,129,0.15); color: #6ee7b7;">Dikembalikan</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Tidak ada transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function toggleCetakDropdown() {
        const menu = document.getElementById('cetakMenu');
        const chevron = document.getElementById('cetakChevron');
        menu.classList.toggle('hidden');
        chevron.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('cetakPdfDropdown');
        const menu = document.getElementById('cetakMenu');
        const chevron = document.getElementById('cetakChevron');
        if (dropdown && !dropdown.contains(e.target)) {
            menu.classList.add('hidden');
            chevron.style.transform = 'rotate(0deg)';
        }
    });
</script>
@endpush
