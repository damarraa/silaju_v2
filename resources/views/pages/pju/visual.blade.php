@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Laporan Visual PJU
            </h2>
            <p class="text-sm text-gray-500 mt-1">Daftar data PJU lengkap dengan bukti foto lapangan.</p>
        </div>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Laporan Visual</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER BAR (Updated) --}}
            <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50"
                x-data="{ 
                    showAdvanced: {{ request()->anyFilled(['kabupaten', 'kecamatan', 'kelurahan', 'trafo_id', 'kondisi', 'verification_status']) ? 'true' : 'false' }} 
                }">

                <form method="GET" action="{{ route('pju.visual') }}" class="space-y-4">

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
                            {{-- Toggle Advanced --}}
                            <button type="button" @click="showAdvanced = !showAdvanced"
                                class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 transition shadow-sm">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="showAdvanced ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                Filter
                            </button>

                            {{-- Excel --}}
                            <button type="submit" formaction="{{ route('pju.export.excel') }}"
                                class="flex items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-3 text-sm font-bold text-white hover:bg-green-700 transition shadow-sm" title="Export Excel">
                                <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                    <path d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                                </svg>
                            </button>

                            {{-- PDF List (Data) --}}
                            <button type="submit" formaction="{{ route('pju.export.pdf') }}"
                                class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-3 text-sm font-bold text-white hover:bg-blue-700 transition shadow-sm" title="PDF Data List">
                                <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                                Data
                            </button>

                            {{-- PDF Visual (Foto) --}}
                            <button type="submit" formaction="{{ route('pju.export.pdfVisual') }}"
                                class="flex items-center justify-center gap-2 rounded-lg bg-red-600 px-3 py-3 text-sm font-bold text-white hover:bg-red-700 transition shadow-sm" title="PDF Visual (Foto)">
                                <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                    <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                    <circle cx="10" cy="11" r="3" />
                                </svg>
                                Visual
                            </button>
                        </div>
                    </div>

                    {{-- Row 2: Basic Filters --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">UP3</label>
                            <select name="area_id" id="filter_area_id" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                                <option value="">Semua UP3</option>
                                @foreach($areas as $area) <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ULP</label>
                            <select name="rayon_id" id="filter_rayon_id" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 disabled:opacity-50">
                                <option value="">Semua ULP</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Status Meter</label>
                            <select name="status" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                                <option value="">Semua Status</option>
                                <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi</option>
                                <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>Non Meter</option>
                                <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 3: Advanced Filters --}}
                    <div x-show="showAdvanced" x-collapse class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 pt-2 border-t border-dashed border-gray-300 dark:border-gray-700">
                        {{-- Kabupaten --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kabupaten</label>
                            <select name="kabupaten" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                                <option value="">Semua</option>
                                @foreach($kabupatens as $kab) <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option> @endforeach
                            </select>
                        </div>
                        {{-- Kecamatan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kecamatan</label>
                            <select name="kecamatan" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 disabled:opacity-50"
                                {{ empty($kecamatans) ? 'disabled' : '' }}>
                                <option value="">Semua</option>
                                @foreach($kecamatans as $kec) <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option> @endforeach
                            </select>
                        </div>
                        {{-- Kelurahan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kelurahan</label>
                            <select name="kelurahan" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 disabled:opacity-50"
                                {{ empty($kelurahans) ? 'disabled' : '' }}>
                                <option value="">Semua</option>
                                @foreach($kelurahans as $kel) <option value="{{ $kel }}" {{ request('kelurahan') == $kel ? 'selected' : '' }}>{{ $kel }}</option> @endforeach
                            </select>
                        </div>
                        {{-- Gardu --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Cari Gardu</label>
                            <input list="trafo_list" name="trafo_id_input"
                                value="{{ $trafos->where('id', request('trafo_id'))->first()->id_gardu ?? '' }}"
                                placeholder="ID Gardu..." onchange="updateTrafoId(this)"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition shadow-sm">
                            <input type="hidden" name="trafo_id" id="trafo_id_hidden" value="{{ request('trafo_id') }}">
                            <datalist id="trafo_list">
                                @foreach($trafos as $trafo) <option data-id="{{ $trafo->id }}" value="{{ $trafo->id_gardu }}">{{ Str::limit($trafo->alamat, 20) }}</option> @endforeach
                            </datalist>
                        </div>
                        {{-- Kondisi --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kondisi</label>
                            <select name="kondisi" onchange="this.form.submit()"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                                <option value="">Semua</option>
                                <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>
                        {{-- Approval & Reset --}}
                        <div class="flex gap-2">
                            <div class="w-full">
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Approval</label>
                                <select name="verification_status" onchange="this.form.submit()"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Valid</option>
                                    <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Tolak</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('pju.visual') }}" class="h-11 w-11 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 transition" title="Reset Filter">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            </div>
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
                            <th class="px-6 py-4 font-bold text-sm uppercase w-10 text-center">No</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase w-48">Foto Lapangan</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase">Identitas & Status</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase">Lokasi PJU</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase">Data Teknis</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                        @forelse($pjus as $pju)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition group">

                                <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ ($pjus->currentPage() - 1) * $pjus->perPage() + $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="h-28 w-40 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm relative cursor-pointer"
                                        onclick="window.open('{{ asset('storage/' . $pju->evidence) }}', '_blank')">
                                        @if($pju->evidence)
                                            <img src="{{ asset('storage/' . $pju->evidence) }}"
                                                class="h-full w-full object-cover group-hover:scale-105 transition duration-500"
                                                alt="Bukti">
                                        @else
                                            <div class="h-full w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">No Image</div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase">ID Pelanggan</p>
                                            <p class="text-base font-bold text-gray-900 dark:text-white font-mono">
                                                {{ $pju->id_pelanggan ?? '-' }}
                                            </p>
                                        </div>
                                        <div>
                                            @if($pju->status === 'meterisasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Meterisasi</span>
                                            @elseif($pju->status === 'non_meterisasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Non-Meter</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ilegal</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if($pju->verification_status == 'verified')
                                                <span class="flex items-center gap-1 text-xs font-bold text-green-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Verified</span>
                                            @elseif($pju->verification_status == 'rejected')
                                                <span class="flex items-center gap-1 text-xs font-bold text-red-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Rejected</span>
                                            @else
                                                <span class="flex items-center gap-1 text-xs font-bold text-yellow-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white line-clamp-3" title="{{ $pju->alamat }}">
                                            {{ $pju->alamat }}
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">
                                            Kel. {{ $pju->kelurahan }}<br>
                                            {{ $pju->area->nama ?? '-' }} - {{ $pju->rayon->nama ?? '-' }}
                                        </span>
                                        <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                            <p class="text-xs text-gray-500">Sumber Gardu:</p>
                                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                                {{ $pju->trafo->id_gardu ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="space-y-2">
                                        <div class="text-sm text-gray-800 dark:text-gray-200">
                                            {{ $pju->jenis_lampu }} <span class="font-bold">{{ $pju->watt }} Watt</span>
                                        </div>
                                        <div class="text-xs text-gray-500">Merk: {{ $pju->merk_lampu }}</div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $pju->kondisi_lampu == 'baik' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                                Kondisi: {{ strtoupper($pju->kondisi_lampu) }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500">Daya: {{ $pju->daya ?? '-' }} VA</div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top text-right">
                                    <a href="{{ route('pju.edit', $pju->id) }}"
                                        class="inline-flex items-center justify-center rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
        // Reuse Logic Filter yang sama persis
        function updateTrafoId(input) {
            const hiddenInput = document.getElementById('trafo_id_hidden');
            const list = document.getElementById('trafo_list');
            const options = list.options;
            let found = false;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === input.value) {
                    hiddenInput.value = options[i].getAttribute('data-id');
                    found = true;
                    input.form.submit();
                    break;
                }
            }
            if (!found) hiddenInput.value = '';
        }

        document.addEventListener("DOMContentLoaded", function () {
            const sArea = document.getElementById('filter_area_id');
            const sRayon = document.getElementById('filter_rayon_id');
            const currentRayonId = "{{ request('rayon_id') }}";

            function loadRayons(areaId) {
                sRayon.innerHTML = '<option value="">Memuat...</option>';
                sRayon.disabled = true;
                if (areaId) {
                    fetch(`{{ url('/ajax/rayons') }}/${areaId}`).then(res => res.json()).then(data => {
                        sRayon.innerHTML = '<option value="">Semua ULP</option>';
                        data.forEach(r => {
                            const opt = document.createElement('option');
                            opt.value = r.id; opt.text = r.nama;
                            if (r.id == currentRayonId) opt.selected = true;
                            sRayon.appendChild(opt);
                        });
                        sRayon.disabled = false;
                    });
                } else { sRayon.innerHTML = '<option value="">Semua ULP</option>'; sRayon.disabled = true; }
            }
            sArea.addEventListener('change', function () { loadRayons(this.value); });
            if (sArea.value) { loadRayons(sArea.value); }
        });
    </script>
@endpush