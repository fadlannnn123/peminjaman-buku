@extends('layouts.admin')
@section('title', 'Manajemen Buku')
@section('subtitle', 'INVENTORY')

@section('content')
    <!-- Top Action Card -->
    <div class="rounded-2xl p-6 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold" style="background: rgba(99,102,241,0.15); color: #818cf8;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h2 class="text-xl font-bold text-white">Daftar Buku</h2>
        </div>
        <a href="{{ route('admin.books.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-full hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md shadow-indigo-500/30 gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Buku Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl shadow-sm overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(99,102,241,0.15); color: #818cf8;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="text-sm font-semibold text-gray-300">Total Inventaris</span>
                <span class="px-3 py-1 bg-indigo-500/30 text-indigo-300 border border-indigo-500/50 text-xs font-semibold rounded-full">{{ $books->total() }} Buku</span>
            </div>

            <form method="GET" action="{{ route('admin.books.index') }}" class="flex items-center relative md:w-80">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-full text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;">
                @if(request('search'))
                    <a href="{{ route('admin.books.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest w-16">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nama & Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Stok</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest flex justify-center">Gambar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($books as $book)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-5 text-center">
                                <span class="w-8 h-8 rounded-full text-xs font-bold inline-flex items-center justify-center" style="background: rgba(255,255,255,0.05); color: #9ca3af;">
                                    #{{ $book->id }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-200 text-base group-hover:text-indigo-400 transition-colors">{{ $book->judul }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span class="text-xs text-gray-400 font-medium lowercase">{{ $book->kategori ? $book->kategori->nama_kategori : 'tanpa kategori' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center text-base font-bold text-gray-200">
                                {{ $book->stok }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($book->stok > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2);">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2);">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex justify-center">
                                    @if($book->foto)
                                        <div class="w-12 h-12 rounded-lg overflow-hidden shadow-sm" style="border: 1px solid rgba(255,255,255,0.1);">
                                            <img src="{{ Storage::url($book->foto) }}" alt="FotoBuku" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #6b7280;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.books.edit', $book->id) }}"
                                       class="w-8 h-8 flex items-center justify-center bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-colors shadow-sm" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.05);">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                Tidak ada data buku yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($books->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                {{ $books->links() }}
            </div>
        @endif
    </div>
@endsection
