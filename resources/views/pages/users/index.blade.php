@extends('layouts.app')

@section('content')
    {{-- Header Page --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">User Management</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data pengguna, role, dan penempatan.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20">
                    <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div
            class="mb-4 rounded-lg bg-green-100 px-6 py-4 text-green-700 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{!! session('success') !!}</span>
            </div>
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto text-left">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-sm uppercase w-10 text-center">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">User Detail</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Role / Jabatan</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Unit Rayon</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Tanggal Gabung</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>

                            {{-- Kolom User Detail --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 uppercase border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">@ {{ $user->username }}</p>
                                        @if($user->identity_number)
                                            <span
                                                class="inline-block mt-1 text-[10px] font-mono px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                                ID: {{ $user->identity_number }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Role --}}
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    @php
                                        $color = match ($role->name) {
                                            'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'admin' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'petugas' => 'bg-green-100 text-green-700 border-green-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-bold border {{ $color }} uppercase">
                                        {{ str_replace('_', ' ', $role->name) }}
                                    </span>
                                @endforeach
                            </td>

                            {{-- Kolom Rayon --}}
                            <td class="px-6 py-4 text-sm">
                                @if($user->rayon)
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->rayon->nama }}</span>
                                    <span class="text-xs text-gray-500 block">Kode: {{ $user->rayon->kode_rayon }}</span>
                                @else
                                    <span class="text-gray-400 italic">Non-Rayon (Pusat)</span>
                                @endif
                            </td>

                            {{-- Tanggal Gabung --}}
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit (Placeholder link) --}}
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="p-2 rounded hover:bg-gray-100 text-gray-500 hover:text-blue-600 dark:hover:bg-gray-800 transition"
                                        title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded hover:bg-red-50 text-gray-500 hover:text-red-600 dark:hover:bg-red-900/20 transition"
                                            title="Hapus User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada user yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-800">
            {{ $users->links() }}
        </div>
    </div>
@endsection