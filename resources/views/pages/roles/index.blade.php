@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Roles & Permissions</h2>
            <p class="text-sm text-gray-500 mt-1">Atur hak akses pengguna dalam sistem.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('roles.create') }}"
                class="flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20">
                    <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Tambah Role
            </a>
        </div>
    </div>

    @if(session('success'))
        <div
            class="mb-4 rounded-lg bg-green-100 px-6 py-4 text-green-700 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
            {!! session('success') !!}
        </div>
    @endif

    @if(session('error'))
        <div
            class="mb-4 rounded-lg bg-red-100 px-6 py-4 text-red-700 border border-red-200 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400">
            {!! session('error') !!}
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto text-left">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-sm uppercase w-10 text-center">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Nama Role</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Jumlah User</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 uppercase">
                                    {{ str_replace('_', ' ', $role->name) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="font-medium text-brand-600 dark:text-brand-400">{{ $role->users_count }}</span>
                                Pengguna
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($role->name !== 'super_admin')
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('roles.show', $role->id) }}"
                                            class="p-2 rounded hover:bg-gray-100 text-gray-500 hover:text-green-600 dark:hover:bg-gray-800 transition"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="p-2 rounded hover:bg-gray-100 text-gray-500 hover:text-blue-600 dark:hover:bg-gray-800 transition"
                                            title="Edit Role">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus role ini? User yang menggunakan role ini akan kehilangan akses.');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded hover:bg-red-50 text-gray-500 hover:text-red-600 dark:hover:bg-red-900/20 transition"
                                                title="Hapus Role">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-gray-100 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Protected
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada role tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $roles->links() }}
        </div>
    </div>
@endsection