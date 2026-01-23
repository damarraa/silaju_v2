@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Rekapitulasi Keseluruhan</h2>
            <p class="text-sm text-gray-500 mt-1">Analisa data berdasarkan grouping wilayah kerja.</p>
        </div>

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

        {{-- FILTER COMPLEX --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form action="{{ route('pju.rekap.total') }}" method="GET">

                {{-- SECTION 1: GROUPING MODE --}}
                <div class="mb-4 flex items-center gap-4 border-b border-gray-200 pb-4 dark:border-gray-700">
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Tampilkan Data
                        Berdasarkan:</span>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="group_by" value="gardu" {{ $groupBy == 'gardu' ? 'checked' : '' }}
                            onchange="toggleRayonFilter(false)" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-medium">Per Kode Gardu</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="group_by" value="rayon" {{ $groupBy == 'rayon' ? 'checked' : '' }}
                            onchange="toggleRayonFilter(true)" class="text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-medium">Per Unit Rayon</span>
                    </label>
                </div>

                {{-- SECTION 2: FILTERS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Date Range --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Tanggal Awal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>

                    {{-- Rayon (Akan didisable via JS) --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Filter Rayon</label>
                        <select name="rayon_id" id="filterRayon"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Semua Rayon</option>
                            @foreach($rayons as $r) <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option> @endforeach
                        </select>
                    </div>

                    {{-- Kabupaten --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Filter Kabupaten</label>
                        <select name="kabupaten"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Semua Kabupaten</option>
                            @foreach($kabupatens as $kab) <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option> @endforeach
                        </select>
                    </div>

                    {{-- Status PJU --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Status PJU</label>
                        <select name="status"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi
                            </option>
                            <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>
                                Non-Meterisasi</option>
                            <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                        </select>
                    </div>

                    {{-- Checkbox Nyala Siang --}}
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="nyala_siang" value="1" {{ request('nyala_siang') ? 'checked' : '' }} class="h-5 w-5 rounded border-gray-300 text-yellow-500 focus:ring-yellow-500">
                            <span class="text-sm font-bold text-yellow-700 dark:text-yellow-500">Hanya Isu Nyala
                                Siang</span>
                        </label>
                    </div>

                    <div class="md:col-span-2 lg:col-span-4 mt-2">
                        <button type="submit"
                            class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                            Tampilkan Data
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
                        <th class="px-6 py-4 font-bold text-sm uppercase w-10">No</th>

                        {{-- Header Dinamis --}}
                        <th class="px-6 py-4 font-bold text-sm uppercase">
                            {{ $groupBy == 'rayon' ? 'Nama Unit Rayon' : 'Kode Gardu & Lokasi' }}
                        </th>

                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Total Titik</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Total Daya</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-center">Rincian Status</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-center">Isu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($data as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>

                            {{-- Kolom Utama Dinamis --}}
                            <td class="px-6 py-4">
                                @if($groupBy == 'rayon')
                                    <span
                                        class="font-bold text-gray-900 dark:text-white">{{ $row->rayon->nama ?? 'Rayon Tidak Diketahui' }}</span>
                                    <div class="text-xs text-gray-500">{{ $row->rayon->kode_rayon ?? '' }}</div>
                                @else
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-gray-900 dark:text-white font-mono">{{ $row->trafo->id_gardu ?? 'Tanpa ID' }}</span>
                                        <span class="text-xs text-gray-500">{{ $row->trafo->alamat ?? '-' }}</span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right font-bold">{{ number_format($row->total_titik) }}</td>

                            <td class="px-6 py-4 text-right font-mono text-brand-600 dark:text-brand-400">
                                {{ number_format($row->total_watt) }} W
                            </td>

                            {{-- Rincian Status --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 text-xs font-medium">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded" title="Meterisasi">M:
                                        {{ $row->count_meter }}</span>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded" title="Non-Meterisasi">NM:
                                        {{ $row->count_non }}</span>
                                    @if($row->count_ilegal > 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded" title="Ilegal">IL:
                                            {{ $row->count_ilegal }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Isu (Nyala Siang) --}}
                            <td class="px-6 py-4 text-center">
                                @if($row->count_nyala_siang > 0)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                        ⚠️ {{ $row->count_nyala_siang }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-800">
            {{ $data->links() }}
        </div>
    </div>

    {{-- Script untuk Disable Filter Rayon --}}
    <script>
        function toggleRayonFilter(disable) {
            const select = document.getElementById('filterRayon');
            if (disable) {
                select.value = ""; // Reset value jika didisable
                select.setAttribute('disabled', 'disabled');
            } else {
                select.removeAttribute('disabled');
            }
        }

        // Jalankan saat load halaman (untuk handle kondisi setelah submit/refresh)
        document.addEventListener('DOMContentLoaded', function () {
            // Cek radio button mana yang checked
            const isRayonGroup = document.querySelector('input[name="group_by"][value="rayon"]').checked;
            toggleRayonFilter(isRayonGroup);
        });
    </script>
@endsection