@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('subtitle', 'Ubah data kategori buku')

@section('content')
    <div class="max-w-xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.kategori.update', $kategori->id_kategori) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama_kategori') border-red-500 focus:ring-red-500 @enderror">
                @error('nama_kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.kategori.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
