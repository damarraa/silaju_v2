@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Detail Pengguna
        </h2>
        
        <div class="flex gap-2">
            <a href="{{ route('users.index') }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <a href="{{ route('users.edit', $user->id) }}" class="flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        {{-- KOLOM KIRI: Profile Card --}}
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col items-center">
                    {{-- Avatar --}}
                    <div class="relative mb-4 h-24 w-24 rounded-full bg-brand-100 flex items-center justify-center text-3xl font-bold text-brand-600 border-4 border-white shadow-sm dark:border-gray-800">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    
                    <h3 class="mb-1 text-xl font-bold text-black dark:text-white text-center">
                        {{ $user->name }}
                    </h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center mb-4">
                        {{ '@' . $user->username }}
                    </p>

                    {{-- Role Badge --}}
                    @foreach($user->roles as $role)
                        @php
                            $color = match($role->name) {
                                'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'admin'       => 'bg-blue-100 text-blue-700 border-blue-200',
                                'petugas'     => 'bg-green-100 text-green-700 border-green-200',
                                default       => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <span class="inline-flex px-4 py-1 rounded-full text-sm font-bold border {{ $color }} uppercase shadow-sm">
                            {{ str_replace('_', ' ', $role->name) }}
                        </span>
                    @endforeach
                    
                    @if($user->identity_number)
                        <div class="mt-6 w-full rounded-lg bg-gray-50 p-3 text-center border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <span class="block text-xs font-medium text-gray-500 uppercase">ID Petugas</span>
                            <span class="block text-lg font-mono font-bold text-black dark:text-white tracking-wider">
                                {{ $user->identity_number }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Detail Info --}}
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 py-4 px-6 dark:border-gray-800">
                    <h3 class="font-medium text-black dark:text-white">Informasi Lengkap</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        
                        {{-- Email --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-500">Email Address</label>
                            <p class="text-base font-medium text-black dark:text-white">{{ $user->email }}</p>
                        </div>

                        {{-- Tanggal Bergabung --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                            <p class="text-base font-medium text-black dark:text-white">
                                {{ $user->created_at->translatedFormat('d F Y') }}
                                <span class="text-xs text-gray-400">({{ $user->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        {{-- Unit Rayon --}}
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-500">Unit Rayon / Wilayah Kerja</label>
                            @if($user->rayon)
                                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="h-10 w-10 flex items-center justify-center rounded bg-white shadow-sm dark:bg-gray-700">
                                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-black dark:text-white">{{ $user->rayon->nama }}</p>
                                        <p class="text-xs text-gray-500">Kode Rayon: {{ $user->rayon->kode_rayon }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-3 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800">
                                    User ini tidak terikat pada Rayon tertentu (Staff Pusat/Admin).
                                </div>
                            @endif
                        </div>

                        {{-- Statistik Singkat (Opsional) --}}
                        @if($user->hasRole('petugas'))
                        <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-2 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-black dark:text-white mb-3">Statistik Kinerja</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="rounded bg-blue-50 p-3 dark:bg-blue-900/20">
                                    <span class="block text-2xl font-bold text-blue-600">{{ $user->pjus()->count() }}</span>
                                    <span class="text-xs text-blue-600/80">Total Input Data</span>
                                </div>
                                {{-- Bisa tambah stat lain disini --}}
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Zona Bahaya --}}
            <div class="mt-6 rounded-lg border border-red-100 bg-red-50 p-6 dark:border-red-900/30 dark:bg-red-900/10">
                <h3 class="font-bold text-red-600 mb-2">Danger Zone</h3>
                <p class="text-sm text-red-600/70 mb-4">Menghapus user akan menghilangkan akses mereka ke sistem. Data inputan mereka (PJU) mungkin tetap ada tetapi statusnya akan menjadi milik 'Unknown User'.</p>
                
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini PERMANEN?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                        Hapus User Permanen
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection