@extends('layouts.admin')
@section('title', 'Tambah Anggota')
@section('subtitle', 'Daftarkan siswa menjadi anggota perpustakaan')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.members.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Akun Siswa</label>
                    <select id="user_id" name="user_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Pilih Siswa yang Belum Menjadi Anggota --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror">
                    @error('no_telepon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    Daftarkan Anggota
                </button>
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
