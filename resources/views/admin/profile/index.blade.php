@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">
        <x-card title="Data Profil">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('name') border-red-500 @enderror" required maxlength="100">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('email') border-red-500 @enderror" required>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">Simpan</button>
                </div>
            </form>
        </x-card>

        <x-card title="Ubah Password">
            <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('current_password') border-red-500 @enderror" required>
                    @error('current_password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('password') border-red-500 @enderror" required>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors" required>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">Ubah Password</button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
