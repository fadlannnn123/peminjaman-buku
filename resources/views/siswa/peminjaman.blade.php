@extends('layouts.siswa')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Peminjaman Buku</h1>
        <p class="text-gray-400 mt-1">Pilih buku yang ingin Anda pinjam</p>
    </div>

    <!-- Search -->
    <div class="mb-8">
        <form method="GET" action="{{ route('siswa.peminjaman') }}" class="flex flex-col md:flex-row items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku berdasarkan judul atau penulis..."
                class="px-5 py-3 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full md:w-96 shadow-sm" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;">
            <button type="submit" class="w-full md:w-auto px-6 py-3 text-sm font-bold rounded-xl transition-all shadow-md gap-2" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('siswa.peminjaman') }}" class="w-full md:w-auto text-center px-6 py-3 text-sm font-bold rounded-xl transition-all" style="background: rgba(255,255,255,0.05); color: #9ca3af; border: 1px solid rgba(255,255,255,0.1);">Reset</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($books as $book)
            <div class="rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <!-- Menampilkan Foto Buku -->
                @if($book->foto)
                    <div class="w-full h-48 overflow-hidden cursor-pointer relative" style="border-bottom: 1px solid rgba(255,255,255,0.05);" onclick="openLightbox('{{ Storage::url($book->foto) }}', '{{ addslashes($book->judul) }}')">
                        <img src="{{ Storage::url($book->foto) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-500 flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="w-full h-48 flex flex-col items-center justify-center" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05); color: #4b5563;">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-xs font-semibold tracking-wide uppercase">Cover Belum Tersedia</span>
                    </div>
                @endif

                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-200 text-lg leading-tight group-hover:text-indigo-400 transition-colors">{{ $book->judul }}</h3>
                    </div>
                    @if($book->kategori)
                        <div class="mb-2">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md" style="background: rgba(255,255,255,0.05); color: #9ca3af;">{{ $book->kategori->nama_kategori }}</span>
                        </div>
                    @endif
                    <p class="text-gray-400 text-sm flex-1">{{ $book->penulis }}</p>
                    
                    <div class="mt-5 pt-5 flex items-center justify-between" style="border-top: 1px solid rgba(255,255,255,0.05);">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2);">
                            Stok: {{ $book->stok }}
                        </span>
                        
                        <form method="POST" action="{{ route('siswa.peminjaman.store') }}" id="form-pinjam-{{ $book->id }}" class="flex items-end gap-2">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $book->id }}">
                            <div class="w-28 text-left">
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Rencana Kembali</label>
                                <input type="date" name="tanggal_kembali" required min="{{ date('Y-m-d') }}" class="w-full text-xs font-medium rounded-xl px-2.5 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0; color-scheme: dark;">
                            </div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-xl transition-all shadow-md gap-1" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Pinjam
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 rounded-2xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <p class="text-gray-400 font-medium">Tidak ada buku yang tersedia saat ini</p>
            </div>
        @endforelse
    </div>
{{-- Lightbox Modal --}}
<div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);" onclick="closeLightbox()">
    <div class="relative max-w-3xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-white/70 hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="lightbox-img" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl" style="border: 1px solid rgba(255,255,255,0.1);">
        <p id="lightbox-title" class="text-center text-white/80 text-sm font-semibold mt-3"></p>
    </div>
</div>

<style>
#lightbox { opacity: 0; transition: opacity 0.25s ease; }
#lightbox.show { opacity: 1; }
</style>

<script>
function openLightbox(src, title) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-title').textContent = title;
    lb.classList.remove('hidden');
    lb.classList.add('flex');
    requestAnimationFrame(() => lb.classList.add('show'));
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.remove('show');
    setTimeout(() => {
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        document.body.style.overflow = '';
    }, 250);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endsection
