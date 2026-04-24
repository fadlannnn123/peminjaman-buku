@extends('layouts.siswa')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Pengembalian Buku</h1>
        <p class="text-gray-400 mt-1">Ajukan pengembalian buku yang sedang Anda pinjam</p>
    </div>

    @if($peminjamans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($peminjamans as $peminjaman)
                <div class="rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="h-2 bg-gradient-to-r {{ $peminjaman->isLate() ? 'from-red-500 to-rose-600' : 'from-indigo-500 to-purple-600' }}"></div>
                    <div class="p-6">
                        
                        <div class="mb-5">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-3">BUKU YANG DIPINJAM</span>
                            <div class="space-y-2">
                                @foreach($peminjaman->detailPeminjaman as $d)
                                    <div class="flex items-center gap-3 p-3 rounded-xl" style="background: rgba(255,255,255,0.03);">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background: rgba(99,102,241,0.15); color: #818cf8;">{{ $d->jumlah }}x</div>
                                        <span class="font-medium text-gray-200">{{ $d->buku->judul }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm pt-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                            <span class="flex items-center gap-1.5 text-gray-400 font-medium">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Pinjam: {{ $peminjaman->tanggal_pinjam->format('d M y') }}
                            </span>
                            <span class="flex items-center gap-1.5 text-gray-400 font-medium">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Batas Kembali: <strong class="text-gray-200">{{ $peminjaman->tanggal_kembali->format('d M y') }}</strong>
                            </span>
                            @if($peminjaman->isLate())
                                <span class="flex items-center gap-1.5 text-red-400 font-bold px-3 py-1 rounded-full text-xs" style="background: rgba(239,68,68,0.1);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Telat {{ $peminjaman->lateDays() }} Hari
                                </span>
                            @endif
                        </div>

                        <div class="mt-6 pt-5" style="border-top: 1px solid rgba(255,255,255,0.05);">
                            @if($peminjaman->status_pinjaman === 'menunggu_pengembalian')
                                <!-- Status Menunggu Konfirmasi -->
                                <div class="p-5 rounded-xl border" style="background: rgba(139,92,246,0.1); border-color: rgba(139,92,246,0.2);">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-purple-300">Menunggu Konfirmasi Admin</h4>
                                            <p class="text-xs text-purple-400 mt-0.5">Pengembalian Anda sedang diproses</p>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $lateDays = $peminjaman->lateDays();
                                        $dendaTerlambat = $lateDays * 5000;
                                        $kondisi = $peminjaman->pengembalian ? $peminjaman->pengembalian->kondisi_buku : 'baik';
                                        $dendaKondisi = 0;
                                        if ($kondisi === 'hilang') $dendaKondisi = 50000;
                                        elseif ($kondisi === 'rusak') $dendaKondisi = 20000;
                                        $totalEstimasi = $dendaTerlambat + $dendaKondisi;
                                    @endphp

                                    @if($totalEstimasi > 0)
                                        <div class="mt-4 pt-4 border-t border-purple-500/20">
                                            <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Estimasi Denda
                                            </p>
                                            @if($dendaTerlambat > 0)
                                                <div class="flex justify-between text-xs text-red-300 mb-1.5">
                                                    <span>Terlambat {{ $lateDays }} Hari</span>
                                                    <span>Rp {{ number_format($dendaTerlambat, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            @if($dendaKondisi > 0)
                                                <div class="flex justify-between text-xs text-red-300 mb-1.5">
                                                    <span>Kondisi: {{ ucfirst($kondisi) }}</span>
                                                    <span>Rp {{ number_format($dendaKondisi, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between text-sm font-bold text-red-400 mt-2 pt-2 border-t border-red-500/20">
                                                <span>Total Estimasi Denda</span>
                                                <span>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2 pt-3 border-t border-purple-500/20">
                                            <p class="text-xs text-emerald-400 font-medium flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Tidak ada denda yang dikenakan
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Form Pengajuan Pengembalian -->
                                <form method="POST" action="{{ route('siswa.pengembalian.store') }}">
                                    @csrf
                                    <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">
                                    
                                    <div class="mb-5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-3">Kondisi Buku Saat Dikembalikan</label>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <label class="flex-1 flex items-center gap-2 cursor-pointer p-3 rounded-xl transition-all hover:bg-white/[0.04] border border-transparent has-[:checked]:bg-indigo-500/10 has-[:checked]:border-indigo-500/30">
                                                <input type="radio" name="kondisi_buku" value="baik" checked class="text-indigo-500 focus:ring-indigo-500 bg-gray-800 border-gray-600 kondisi-radio" data-peminjaman-id="{{ $peminjaman->id }}" data-denda="0">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-200">Baik</span>
                                                </div>
                                            </label>
                                            <label class="flex-1 flex items-center gap-2 cursor-pointer p-3 rounded-xl transition-all hover:bg-white/[0.04] border border-transparent has-[:checked]:bg-amber-500/10 has-[:checked]:border-amber-500/30">
                                                <input type="radio" name="kondisi_buku" value="rusak" class="text-amber-500 focus:ring-amber-500 bg-gray-800 border-gray-600 kondisi-radio" data-peminjaman-id="{{ $peminjaman->id }}" data-denda="20000">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-amber-400">Rusak</span>
                                                    <span class="text-[10px] text-amber-500/70">Denda Rp 20.000</span>
                                                </div>
                                            </label>
                                            <label class="flex-1 flex items-center gap-2 cursor-pointer p-3 rounded-xl transition-all hover:bg-white/[0.04] border border-transparent has-[:checked]:bg-red-500/10 has-[:checked]:border-red-500/30">
                                                <input type="radio" name="kondisi_buku" value="hilang" class="text-red-500 focus:ring-red-500 bg-gray-800 border-gray-600 kondisi-radio" data-peminjaman-id="{{ $peminjaman->id }}" data-denda="50000">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-red-400">Hilang</span>
                                                    <span class="text-[10px] text-red-500/70">Denda Rp 50.000</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Dynamic Total Estimasi -->
                                    <div id="estimasi-container-{{ $peminjaman->id }}" class="mb-5 p-4 rounded-xl border border-red-500/20 bg-red-500/10 hidden" data-late-days="{{ $peminjaman->lateDays() }}">
                                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Estimasi Denda Saat Ini
                                        </p>
                                        @if($peminjaman->isLate())
                                            <div class="flex justify-between text-xs text-red-300 mb-1.5">
                                                <span>Terlambat {{ $peminjaman->lateDays() }} Hari</span>
                                                <span>Rp {{ number_format($peminjaman->lateDays() * 5000, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        <div id="denda-kondisi-row-{{ $peminjaman->id }}" class="flex justify-between text-xs text-red-300 mb-1.5 hidden">
                                            <span id="denda-kondisi-label-{{ $peminjaman->id }}">Kondisi</span>
                                            <span id="denda-kondisi-value-{{ $peminjaman->id }}">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between text-sm font-bold text-red-400 mt-2 pt-2 border-t border-red-500/20">
                                            <span>Total Estimasi</span>
                                            <span id="total-estimasi-{{ $peminjaman->id }}">Rp 0</span>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="w-full py-3 text-sm font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        Ajukan Pengembalian
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 rounded-2xl shadow-sm border" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05);">
            <div class="w-20 h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-200">Tidak Ada Peminjaman Aktif</h3>
            <p class="text-gray-400 mt-1">Anda sudah mengembalikan semua buku yang dipinjam.</p>
            <a href="{{ route('siswa.peminjaman') }}"
               class="inline-flex items-center mt-6 px-6 py-3 text-sm font-bold rounded-xl transition-all shadow-md gap-2" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Pinjam Buku
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.kondisi-radio');
        
        // Fungsi format Rupiah
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('Rp', 'Rp ').replace(',00', '');
        };

        // Trigger perhitungan awal untuk semua peminjaman yang late
        document.querySelectorAll('[id^="estimasi-container-"]').forEach(container => {
            const lateDays = parseInt(container.getAttribute('data-late-days')) || 0;
            if(lateDays > 0) {
                container.classList.remove('hidden');
                const id = container.id.split('-')[2];
                document.getElementById('total-estimasi-' + id).textContent = formatRupiah(lateDays * 5000);
            }
        });
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const peminjamanId = this.getAttribute('data-peminjaman-id');
                const dendaKondisi = parseInt(this.getAttribute('data-denda'));
                const container = document.getElementById('estimasi-container-' + peminjamanId);
                const lateDays = parseInt(container.getAttribute('data-late-days')) || 0;
                
                const totalDenda = (lateDays * 5000) + dendaKondisi;
                
                if (totalDenda > 0) {
                    container.classList.remove('hidden');
                    
                    const rowKondisi = document.getElementById('denda-kondisi-row-' + peminjamanId);
                    if (dendaKondisi > 0) {
                        rowKondisi.classList.remove('hidden');
                        document.getElementById('denda-kondisi-label-' + peminjamanId).textContent = 'Kondisi: ' + (this.value === 'rusak' ? 'Rusak' : 'Hilang');
                        document.getElementById('denda-kondisi-value-' + peminjamanId).textContent = formatRupiah(dendaKondisi);
                    } else {
                        rowKondisi.classList.add('hidden');
                    }
                    
                    document.getElementById('total-estimasi-' + peminjamanId).textContent = formatRupiah(totalDenda);
                } else {
                    container.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush
