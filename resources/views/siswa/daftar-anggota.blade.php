@extends('layouts.siswa')
@section('title', 'Pendaftaran Anggota')
@section('subtitle', 'Lengkapi biodata untuk jadi anggota perpustakaan')

@section('content')
    <div class="max-w-2xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100/50 border border-gray-100 overflow-hidden">
            <div class="p-8 text-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold">Pendaftaran Anggota</h2>
                <p class="text-indigo-100 mt-2 text-sm">Lengkapi data di bawah ini untuk mengaktifkan kartu anggota Anda.</p>
            </div>

            <div class="p-8 bg-white">
                <form method="POST" action="{{ route('siswa.daftar-anggota.store') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Domisili</label>
                        <textarea id="alamat" name="alamat" rows="3" required placeholder="Masukkan alamat lengkap Anda..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-8">
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp / Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror">
                        @error('no_telepon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md shadow-indigo-500/30 flex items-center justify-center gap-2">
                        <span>Aktifkan Anggota Sekarang</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('siswa.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">Nanti Dulu</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
