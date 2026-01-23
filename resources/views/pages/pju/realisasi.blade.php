@extends('layouts.app')

@section('content')
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Laporan Realisasi PJU</h2>
            <p class="text-sm text-gray-500 mt-1">Rekapitulasi beban daya dan kondisi aset per Gardu.</p>
        </div>

        {{-- Tombol Export Placeholder --}}
        <div class="flex gap-2">
            <button type="button"
                class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition shadow-sm">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20">
                    <path
                        d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                </svg>
                Excel
            </button>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER BAR (GRID SYSTEM 5 KOLOM) --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form action="{{ route('pju.realisasi') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- 1. Search --}}
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Gardu..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition shadow-sm">
                    </div>

                    {{-- 2. Rayon --}}
                    <div>
                        <select name="rayon_id" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white hover:border-gray-400">
                            <option value="">Semua Rayon</option>
                            @foreach($rayons as $r)
                                <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Kabupaten --}}
                    <div>
                        <select name="kabupaten" onchange="resetKecamatan(); this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white hover:border-gray-400">
                            <option value="">Semua Kabupaten</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Kecamatan (Disabled jika Kabupaten belum dipilih) --}}
                    <div>
                        <select name="kecamatan" onchange="resetKelurahan(); this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed hover:border-gray-400"
                            {{ empty($kecamatans) ? 'disabled' : '' }}>
                            <option value="">{{ empty($kecamatans) ? 'Pilih Kabupaten Dulu' : 'Semua Kecamatan' }}</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 5. Kelurahan (Disabled jika Kecamatan belum dipilih) --}}
                    <div>
                        <select name="kelurahan" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed hover:border-gray-400"
                            {{ empty($kelurahans) ? 'disabled' : '' }}>
                            <option value="">{{ empty($kelurahans) ? 'Pilih Kecamatan Dulu' : 'Semua Kelurahan' }}</option>
                            @foreach($kelurahans as $kel)
                                <option value="{{ $kel }}" {{ request('kelurahan') == $kel ? 'selected' : '' }}>{{ $kel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>

        {{-- TABEL DATA --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-bordered border-collapse text-left text-sm min-w-[1000px]">
                <thead
                    class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-center uppercase font-bold text-xs">
                    {{-- Header Level 1 --}}
                    <tr>
                        <th rowspan="2"
                            class="border border-gray-300 dark:border-gray-700 px-4 py-2 bg-gray-200 dark:bg-gray-700 align-middle w-40">
                            Kode Trafo & Lokasi</th>

                        <th colspan="3"
                            class="border border-gray-300 dark:border-gray-700 px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            Meterisasi</th>

                        <th colspan="3"
                            class="border border-gray-300 dark:border-gray-700 px-2 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300">
                            Non-Meterisasi</th>

                        <th colspan="3"
                            class="border border-gray-300 dark:border-gray-700 px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                            Kondisi Rusak</th>

                        <th rowspan="2"
                            class="border border-gray-300 dark:border-gray-700 px-4 py-2 bg-gray-200 dark:bg-gray-700 align-middle w-24">
                            Total<br>Titik</th>
                    </tr>

                    {{-- Header Level 2 --}}
                    <tr>
                        {{-- Meterisasi --}}
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-16">Jml</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">Watt</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">VA</th>

                        {{-- Non-Meter --}}
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-16">Jml</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">Watt</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">VA</th>

                        {{-- Rusak --}}
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-16">Jml</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">Watt</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-2 py-2 w-20">VA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-center">
                    @forelse($realisasi as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-gray-300 transition">

                            {{-- Kode Trafo & Lokasi --}}
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-3 text-left align-middle">
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white font-mono">{{ $row->trafo->id_gardu ?? 'Tanpa ID' }}</span>
                                    {{-- Menampilkan Kecamatan/Kelurahan kecil di bawahnya --}}
                                    <span class="text-[10px] text-gray-500 font-normal leading-tight mt-1">
                                        {{ $row->trafo->kelurahan ?? '-' }}, {{ $row->trafo->kecamatan ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Meterisasi --}}
                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-3">
                                {{ number_format($row->met_count) }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-3">
                                {{ number_format($row->met_watt) }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-3">{{ number_format($row->met_va) }}
                            </td>

                            {{-- Non Meter --}}
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 bg-orange-50 dark:bg-orange-900/10">
                                {{ number_format($row->non_count) }}</td>
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 bg-orange-50 dark:bg-orange-900/10">
                                {{ number_format($row->non_watt) }}</td>
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 bg-orange-50 dark:bg-orange-900/10">
                                {{ number_format($row->non_va) }}</td>

                            {{-- Rusak --}}
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 text-red-600 font-medium bg-red-50/50 dark:bg-red-900/10">
                                {{ number_format($row->rusak_count) }}</td>
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 text-red-600 bg-red-50/50 dark:bg-red-900/10">
                                {{ number_format($row->rusak_watt) }}</td>
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-2 py-3 text-red-600 bg-red-50/50 dark:bg-red-900/10">
                                {{ number_format($row->rusak_va) }}</td>

                            {{-- Total --}}
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-4 py-3 font-bold bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
                                {{ number_format($row->total_titik) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p>Belum ada data realisasi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-800">
            {{ $realisasi->links() }}
        </div>
    </div>

    {{-- Script Reset Filter (UX Improvement) --}}
    <script>
        // Fungsi untuk reset dropdown bawahan saat atasan berubah
        function resetKecamatan() {
            const kecSelect = document.querySelector('select[name="kecamatan"]');
            const kelSelect = document.querySelector('select[name="kelurahan"]');
            if (kecSelect) kecSelect.value = "";
            if (kelSelect) kelSelect.value = "";
        }

        function resetKelurahan() {
            const kelSelect = document.querySelector('select[name="kelurahan"]');
            if (kelSelect) kelSelect.value = "";
        }
    </script>
@endsection