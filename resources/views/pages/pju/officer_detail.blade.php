@extends('layouts.app')

@section('content')
    {{-- Header & Navigasi --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('pju.officers') }}" class="p-2 rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">Rincian Kinerja Petugas</h2>
                <p class="text-sm text-gray-500">Detail input per gardu.</p>
            </div>
        </div>
        
        {{-- Tombol Export Khusus Halaman Ini --}}
        <div class="flex gap-2">
            <a href="{{ route('pju.officers.export.excel', $user->id) }}" target="_blank" class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20"><path d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z"/></svg> Excel
            </a>
            <a href="{{ route('pju.officers.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20"><path d="M6 2C4.89543 2 4 2.89543 4 4V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V6L12 2H6Z" stroke="none"/><path d="M11 2V7H16" fill="white" fill-opacity="0.3"/></svg> PDF
            </a>
        </div>
    </div>

    {{-- KARTU PROFIL PETUGAS --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="h-16 w-16 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold text-2xl dark:bg-brand-900/50 dark:text-brand-400">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div class="grow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-500">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Rayon: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $user->rayon->nama ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase font-bold">Total Input</p>
                <p class="text-3xl font-bold text-brand-600 dark:text-brand-400">{{ $user->pjus()->count() }}</p>
            </div>
        </div>
    </div>

    {{-- TABEL RINCIAN --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 p-5 dark:border-gray-800">
            <form action="{{ url()->current() }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Gardu / Alamat..." class="w-full sm:w-1/3 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </form>
        </div>
        
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto text-left">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-sm uppercase w-10">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">ID Gardu (Trafo)</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Lokasi Gardu</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Jumlah PJU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($details as $detail)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ ($details->currentPage() - 1) * $details->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-brand-600 dark:text-brand-400 font-mono">
                            {{ $detail->trafo->id_gardu ?? 'Tanpa Gardu' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $detail->trafo->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $detail->total_input }} Data
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada input data pada gardu manapun.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $details->links() }}
        </div>
    </div>
@endsection