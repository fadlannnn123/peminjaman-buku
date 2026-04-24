@extends('layouts.admin')
@section('title', 'Edit Anggota')
@section('subtitle', 'Ubah data keanggotaan siswa')

@section('content')
    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        
        <div class="mb-6 p-4 bg-indigo-50 rounded-xl border border-indigo-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                {{ strtoupper(substr($member->user->name, 0, 2)) }}
            </div>
            <div>
                <h4 class="font-bold text-gray-800">{{ $member->user->name }}</h4>
                <p class="text-xs text-indigo-600 font-medium">Username: {{ $member->user->username }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.members.update', $member->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat', $member->alamat) }}</textarea>
                    @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $member->no_telepon) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror">
                    @error('no_telepon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
