@extends('layouts.app')

@section('content')
    {{-- HEADER HALAMAN --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Rekapitulasi Harian</h2>
            <p class="text-sm text-gray-500 mt-1">Log aktivitas input data petugas lapangan.</p>
        </div>

        {{-- Tombol Export (Placeholder) --}}
        <div class="flex gap-2">
            <button
                class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20">
                    <path
                        d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                </svg>
                Excel
            </button>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER BAR --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form action="{{ route('pju.rekap.harian') }}" method="GET">
                <div class="flex flex-col gap-4">

                    {{-- Row 1: Tanggal Range & Checkbox Nyala Siang --}}
                    <div class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-auto">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div class="hidden sm:block pb-2 text-gray-400">-</div>
                        <div class="w-full sm:w-auto">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>

                        {{-- Checkbox Nyala Siang (Boolean) --}}
                        <div class="w-full sm:w-auto pb-2 sm:pb-3 sm:ml-4">
                            <label class="flex items-center gap-2 cursor-pointer select-none p-1">
                                <input type="checkbox" name="nyala_siang" value="1" {{ request('nyala_siang') ? 'checked' : '' }} class="h-5 w-5 rounded border-gray-300 text-yellow-500 focus:ring-yellow-500">
                                <span class="text-sm font-bold text-yellow-700 dark:text-yellow-500">
                                    <span class="hidden sm:inline">⚠️</span> Filter Isu Nyala Siang
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Row 2: Wilayah & Petugas --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select name="rayon_id" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Semua Rayon</option>
                            @foreach($rayons as $r) <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option> @endforeach
                        </select>

                        <select name="user_id" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Semua Petugas</option>
                            @foreach($officers as $off) <option value="{{ $off->id }}" {{ request('user_id') == $off->id ? 'selected' : '' }}>{{ $off->name }}</option> @endforeach
                        </select>

                        <button type="submit"
                            class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition shadow-sm">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE DATA --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto text-left">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-sm uppercase w-10 text-center">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Waktu Input</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Petugas</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Lokasi (ID Pel)</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Kondisi & Temuan</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Foto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($pjus as $pju)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ ($pjus->currentPage() - 1) * $pjus->perPage() + $loop->iteration }}</td>

                            {{-- Waktu Input --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white">{{ $pju->created_at->format('d/m/Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $pju->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>

                            {{-- Petugas --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-6 w-6 rounded-full bg-brand-100 flex items-center justify-center text-[10px] font-bold text-brand-700">
                                        {{ substr($pju->user->name ?? '?', 0, 2) }}
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $pju->user->name ?? '-' }}</span>
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 ml-8">{{ $pju->rayon->nama ?? '' }}</div>
                            </td>

                            {{-- Lokasi --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $pju->id_pelanggan ?? 'Non-Meter' }}</span>
                                    <span class="text-xs text-gray-500 truncate w-48"
                                        title="{{ $pju->alamat }}">{{ $pju->alamat }}</span>
                                </div>
                            </td>

                            {{-- Kondisi (Logic Baru) --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start gap-1.5">
                                    {{-- 1. Badge Boolean NYALA SIANG (Prioritas Temuan) --}}
                                    @if($pju->nyala_siang)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 dark:bg-yellow-900/50 dark:text-yellow-300">
                                            <svg class="w-3 h-3 animate-spin-slow" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            NYALA SIANG
                                        </span>
                                    @endif

                                    {{-- 2. Badge Kondisi Fisik --}}
                                    @if($pju->kondisi_lampu == 'rusak')
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-600 border border-red-100 dark:bg-red-900/20 dark:text-red-400">
                                            Fisik: Rusak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-600 border border-green-100 dark:bg-green-900/20 dark:text-green-400">
                                            Fisik: Baik
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Foto Thumbnail --}}
                            <td class="px-6 py-4 text-right">
                                @if($pju->evidence)
                                    <a href="{{ asset('storage/' . $pju->evidence) }}" target="_blank"
                                        class="inline-block h-10 w-10 rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:scale-110 transition hover:shadow-md">
                                        <img src="{{ asset('storage/' . $pju->evidence) }}" class="h-full w-full object-cover">
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">No Img</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>Tidak ada aktivitas input pada rentang tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-800">
            {{ $pjus->links() }}
        </div>
    </div>
@endsection