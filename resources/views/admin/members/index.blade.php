@extends('layouts.admin')
@section('title', 'Data Anggota')
@section('subtitle', 'Daftar pengguna yang terdaftar sebagai anggota perpustakaan')

@section('content')
    <!-- Top Action Card -->
    <div class="rounded-2xl p-6 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold" style="background: rgba(99,102,241,0.15); color: #818cf8;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-white">Daftar Anggota</h2>
        </div>
        <a href="{{ route('admin.members.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-full hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md shadow-indigo-500/30 gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Anggota Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl shadow-sm overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(99,102,241,0.15); color: #818cf8;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-sm font-semibold text-gray-300">Total Anggota</span>
                <span class="px-3 py-1 bg-indigo-500/30 text-indigo-300 border border-indigo-500/50 text-xs font-semibold rounded-full">{{ $members->total() }} Anggota</span>
            </div>

            <form method="GET" action="{{ route('admin.members.index') }}" class="flex items-center relative md:w-80">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, alamat..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-full text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;">
                @if(request('search'))
                    <a href="{{ route('admin.members.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Data Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Alamat & Nomor</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($members as $i => $member)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-5 text-center">
                                <span class="w-8 h-8 rounded-full text-xs font-bold inline-flex items-center justify-center" style="background: rgba(255,255,255,0.05); color: #9ca3af;">
                                    {{ $members->firstItem() + $i }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm" style="background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);">
                                        {{ strtoupper(substr($member->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-gray-200 group-hover:text-indigo-400 transition-colors">{{ $member->user->name }}</p>
                                        <p class="text-xs text-gray-500 font-medium font-mono">{{ '@'.$member->user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-medium text-gray-300">{{ $member->alamat }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $member->no_telepon }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.members.edit', $member->id) }}"
                                       class="w-8 h-8 flex items-center justify-center bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.members.destroy', $member->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-colors shadow-sm" title="Hapus"
                                                onclick="return confirm('Yakin menghapus anggota ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.05);">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                Tidak ada data anggota yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                {{ $members->links() }}
            </div>
        @endif
    </div>
@endsection
