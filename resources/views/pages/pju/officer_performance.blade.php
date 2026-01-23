@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Rekapitulasi Data Petugas
            </h2>
            <p class="text-sm text-gray-500 mt-1">Monitoring jumlah input data per petugas lapangan.</p>
        </div>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Data Petugas</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER BAR --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form method="GET" action="{{ route('pju.officers') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                    {{-- Search --}}
                    <div class="relative w-full md:w-1/3">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama Petugas / Rayon..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-11 pr-4 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition shadow-sm" />
                    </div>

                    {{-- Filter & Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">

                        {{-- Filter Rayon --}}
                        <select name="rayon_id" onchange="this.form.submit()"
                            class="h-11 w-full sm:w-48 rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="">Semua Rayon</option>
                            @foreach($rayons as $rayon)
                                <option value="{{ $rayon->id }}" {{ request('rayon_id') == $rayon->id ? 'selected' : '' }}>
                                    {{ $rayon->nama }}</option>
                            @endforeach
                        </select>

                        {{-- Export Button (Placeholder) --}}
                        <button type="button"
                            class="flex items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20">
                                <path
                                    d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE DATA --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto text-left">
                <thead
                    class="border-b border-gray-200 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-sm uppercase w-16 text-center">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Kode Rayon</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Nama Rayon</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Kode Petugas</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Nama Petugas</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Kinerja Input</th> {{-- Ganti Title
                        --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse($officers as $officer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition group">
                            <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                {{ ($officers->currentPage() - 1) * $officers->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $officer->rayon->kode_rayon ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $officer->rayon->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 font-mono dark:text-gray-400">
                                {{-- Gunakan ID atau NIP jika ada kolom khusus --}}
                                {{ str_pad($officer->identity_number ?? $officer->id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-9 w-9 rounded-full overflow-hidden bg-brand-100 border border-brand-200 flex items-center justify-center text-brand-700 font-bold text-xs">
                                        {{ substr($officer->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $officer->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $officer->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- MODIFIKASI KOLOM AKSI --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end gap-1.5">
                                    {{-- 1. Badge Angka (Lebih Menonjol) --}}
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400">
                                        {{ $officer->pjus_count }} Data
                                    </span>

                                    {{-- 2. Link Rincian (Rapih di bawahnya) --}}
                                    <a href="{{ route('pju.officers.detail', $officer->id) }}"
                                        class="group flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-brand-600 transition-colors">
                                        Lihat Rincian
                                        <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data petugas ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-800">
            {{ $officers->links() }}
        </div>
    </div>
@endsection