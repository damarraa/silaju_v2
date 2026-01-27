@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Rekapitulasi Data PJU
            </h2>
            <p class="text-sm text-gray-500 mt-1">Tampilan fokus data teks (ID Pelanggan, Alamat, & Daya).</p>
        </div>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Rekap Data</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER BAR --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form method="GET" action="{{ route('pju.meterisasi') }}" class="space-y-4">

                {{-- Row 1: Search & Export --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative w-full md:w-1/2">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari ID Pelanggan / Alamat / Merk..."
                            class="h-12 w-full rounded-lg border border-gray-300 bg-white pl-11 pr-4 text-base focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition shadow-sm" />
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <a href="{{ route('pju.export.excel', request()->query()) }}" target="_blank"
                            class="flex flex-1 md:flex-none items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700 transition shadow-sm">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                <path
                                    d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('pju.export.pdf', request()->query()) }}" target="_blank"
                            class="flex flex-1 md:flex-none items-center justify-center gap-2 rounded-lg bg-red-600 px-6 py-3 text-sm font-bold text-white hover:bg-red-700 transition shadow-sm">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                <path
                                    d="M6 2C4.89543 2 4 2.89543 4 4V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V6L12 2H6Z"
                                    stroke="none" />
                                <path d="M11 2V7H16" fill="white" fill-opacity="0.3" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                {{-- Row 2: Detailed Filters --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

                    {{-- 1. Area --}}
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Area</label>
                        <select name="area_id" id="filter_area_id" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="">Semua</option>
                            @foreach($areas as $area) <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nama }}</option> @endforeach
                        </select>
                    </div>

                    {{-- 2. Rayon --}}
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Rayon</label>
                        <select name="rayon_id" id="filter_rayon_id" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 disabled:opacity-50">
                            <option value="">Semua</option>
                        </select>
                    </div>

                    {{-- 3. Gardu (NEW FILTER) --}}
                    <div class="relative sm:col-span-2 lg:col-span-1 xl:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Cari Gardu</label>
                        <input list="trafo_list" name="trafo_id_input"
                            value="{{ $trafos->where('id', request('trafo_id'))->first()->id_gardu ?? '' }}"
                            placeholder="Ketik ID Gardu..." onchange="updateTrafoId(this)"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition shadow-sm">
                        <input type="hidden" name="trafo_id" id="trafo_id_hidden" value="{{ request('trafo_id') }}">

                        <datalist id="trafo_list">
                            @foreach($trafos as $trafo)
                                <option data-id="{{ $trafo->id }}" value="{{ $trafo->id_gardu }}">
                                    {{ Str::limit($trafo->alamat, 30) }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>

                    {{-- 4. Status --}}
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Status Meter</label>
                        <select name="status" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="">Semua</option>
                            <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi
                            </option>
                            <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>Non
                                Meter</option>
                            <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                        </select>
                    </div>

                    {{-- 5. Kondisi --}}
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kondisi</label>
                        <select name="kondisi" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="">Semua</option>
                            <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>

                    {{-- 6. Verifikasi --}}
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Approval</label>
                        <select name="verification_status" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>
                                Menunggu</option>
                            <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>
                                Terverifikasi</option>
                            <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>
                                Ditolak</option>
                        </select>
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
                        <th class="px-6 py-4 font-bold text-sm uppercase">No</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">ID Pelanggan / Status</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Alamat / Lokasi</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Daya</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase">Gardu</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse($pjus as $pju)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ ($pjus->currentPage() - 1) * $pjus->perPage() + $loop->iteration }}
                            </td>

                            {{-- KOLOM UTAMA: ID PELANGGAN / STATUS --}}
                            <td class="px-6 py-4">
                                @if($pju->status === 'meterisasi')
                                    <span class="text-lg font-bold text-brand-600 dark:text-brand-400 font-mono tracking-wide">
                                        {{ $pju->id_pelanggan }}
                                    </span>
                                @elseif($pju->status === 'non_meterisasi')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                        NON-METER
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        ILEGAL
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[300px]"
                                        title="{{ $pju->alamat }}">
                                        {{ $pju->alamat }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-0.5 uppercase tracking-wider">
                                        {{ $pju->area->nama ?? '-' }} — {{ $pju->rayon->nama ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($pju->daya)
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-300">
                                        {{ $pju->daya }} VA
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $pju->trafo->id_gardu ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('pju.edit', $pju->id) }}"
                                    class="inline-flex items-center justify-center rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                                    Buka
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <p class="text-lg">Tidak ada data ditemukan.</p>
                                    <p class="text-sm">Coba sesuaikan filter status atau kata kunci pencarian.</p>
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

@push('scripts')
    <script>
        // AJAX Rayon (Sama seperti sebelumnya, wajib ada agar filter Rayon jalan)
        document.addEventListener("DOMContentLoaded", function () {
            const sArea = document.getElementById('filter_area_id');
            const sRayon = document.getElementById('filter_rayon_id');
            const currentRayonId = "{{ request('rayon_id') }}";

            function loadRayons(areaId) {
                sRayon.innerHTML = '<option value="">Memuat...</option>';
                sRayon.disabled = true;
                if (areaId) {
                    fetch(`{{ url('/ajax/rayons') }}/${areaId}`).then(res => res.json()).then(data => {
                        sRayon.innerHTML = '<option value="">Semua Rayon</option>';
                        data.forEach(r => {
                            const opt = document.createElement('option');
                            opt.value = r.id; opt.text = r.nama;
                            if (r.id == currentRayonId) opt.selected = true;
                            sRayon.appendChild(opt);
                        });
                        sRayon.disabled = false;
                    });
                } else { sRayon.innerHTML = '<option value="">Semua Rayon</option>'; sRayon.disabled = true; }
            }
            sArea.addEventListener('change', function () { loadRayons(this.value); });
            if (sArea.value) { loadRayons(sArea.value); }
        });
    </script>
@endpush