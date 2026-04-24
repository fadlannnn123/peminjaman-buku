@extends('layouts.admin')
@section('title', 'Tambah Buku')
@section('subtitle', 'Tambahkan buku baru ke katalog perpustakaan')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('judul') border-red-500 @enderror">
                    @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                    <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('penulis') border-red-500 @enderror">
                    @error('penulis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Kategori (Opsional)</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ old('kategori_id') == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok Tersedia</label>
                    <input type="number" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('stok') border-red-500 @enderror">
                    @error('stok')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto / Cover Buku (Opsional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-500 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center mt-2">
                                <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-1 shadow-sm border border-indigo-100">
                                    <span>Pilih File Gambar</span>
                                    <input id="foto" name="foto" type="file" class="sr-only" accept="image/*" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file dipilih'; document.getElementById('file-name').classList.add('text-indigo-600', 'font-bold');">
                                </label>
                            </div>
                            <p id="file-name" class="text-xs text-gray-500 mt-2">Belum ada file dipilih (Maks 5MB)</p>
                        </div>
                    </div>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    Simpan Buku
                </button>
                <a href="{{ route('admin.books.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
