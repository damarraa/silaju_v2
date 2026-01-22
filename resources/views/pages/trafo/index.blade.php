@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Data Trafo / Gardu
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Data Trafo</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        <div class="border-b border-gray-200 p-5 dark:border-gray-800">
            <form method="GET" action="{{ route('trafo.index') }}" class="space-y-5">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="relative w-full sm:w-1/2 md:w-1/3">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari No. Gardu / Alamat..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 pl-10 pr-4 text-sm text-gray-800 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-brand-500 transition" />
                    </div>

                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('trafo.create') }}"
                            class="flex flex-1 sm:flex-none items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 16 16">
                                <path
                                    d="M15 7H9V1C9 0.4 8.6 0 8 0C7.4 0 7 0.4 7 1V7H1C0.4 7 0 7.4 0 8C0 8.6 0.4 9 1 9H7V15C7 15.6 7.4 16 8 16C8.6 16 9 15.6 9 15V9H15C15.6 9 16 8.6 16 8C16 7.4 15.6 7 15 7Z" />
                            </svg>
                            Input Baru
                        </a>
                        <a href="#"
                            class="flex flex-1 sm:flex-none items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-white/5">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                <path
                                    d="M17 12V5H3V12H5V10H15V12H17ZM15 3H5V0H15V3ZM15 16H5V14H15V16ZM18 12H17V14H19V18H1V14H3V12H2C0.9 12 0 12.9 0 14V18C0 19.1 0.9 20 2 20H18C19.1 20 20 19.1 20 18V14C20 12.9 19.1 12 18 12Z" />
                            </svg>
                            Report
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <select name="area_id" id="filter_area_id" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 dark:hover:border-gray-600">
                        <option value="">Semua Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->nama }}</option>
                        @endforeach
                    </select>

                    <select name="rayon_id" id="filter_rayon_id" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 dark:hover:border-gray-600 disabled:opacity-50">
                        <option value="">Semua Rayon</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto">
                <thead class="border-b border-gray-200 bg-gray-50 text-left dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Foto</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">ID Gardu /
                            Lokasi</th>

                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Koordinat</th>

                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Spesifikasi
                        </th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Panel / SR</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase text-right dark:text-gray-400">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($trafos as $trafo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            <td class="px-4 py-3">
                                <div class="h-12 w-12 rounded-md overflow-hidden border border-gray-200 dark:border-gray-700 relative group cursor-pointer"
                                    onclick="window.open('{{ asset('storage/' . $trafo->evidence) }}', '_blank')">
                                    @if($trafo->evidence)
                                        <img src="{{ asset('storage/' . $trafo->evidence) }}"
                                            class="h-full w-full object-cover group-hover:scale-110 transition" alt="Trafo">
                                    @else
                                        <div
                                            class="h-full w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                            N/A</div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-brand-500">
                                        {{ $trafo->id_gardu }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]"
                                        title="{{ $trafo->alamat }}">
                                        {{ Str::limit($trafo->alamat, 40) }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 uppercase">
                                        {{ $trafo->kecamatan }}, {{ $trafo->kelurahan }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs text-gray-600 dark:text-gray-400">
                                    <span class="font-mono">Lat: {{ $trafo->latitude ?? '-' }}</span>
                                    <span class="font-mono">Lng: {{ $trafo->longitude ?? '-' }}</span>
                                    @if($trafo->latitude && $trafo->longitude)
                                        <a href="https://www.google.com/maps?q={{ $trafo->latitude }},{{ $trafo->longitude }}"
                                            target="_blank" class="text-[10px] text-brand-500 hover:underline mt-0.5">
                                            Lihat Peta
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="inline-flex w-fit items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                        {{ $trafo->daya }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $trafo->merk }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-700 dark:text-gray-300">
                                    {{ $trafo->sr ?? '-' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('trafo.edit', $trafo->id) }}"
                                        class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500">
                                        <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24">
                                            <path
                                                d="M2.29 16.29L16.29 2.29C16.68 1.9 17.32 1.9 17.71 2.29L21.71 6.29C22.1 6.68 22.1 7.32 21.71 7.71L7.71 21.71C7.32 22.1 6.68 22.1 6.29 21.71L2.29 17.71C1.9 17.32 1.9 16.68 2.29 16.29ZM19.29 7L17 4.71L15 6.71L17.29 9L19.29 7ZM13 8.71L4.71 17L7 19.29L15.29 11L13 8.71Z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('trafo.destroy', $trafo->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus Trafo ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24">
                                                <path
                                                    d="M19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data Trafo ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $trafos->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // AJAX Rayon Filter Logic
        document.addEventListener("DOMContentLoaded", function () {
            const sArea = document.getElementById('filter_area_id');
            const sRayon = document.getElementById('filter_rayon_id');
            const currentRayonId = "{{ request('rayon_id') }}";

            function loadRayons(areaId) {
                sRayon.innerHTML = '<option value="">Memuat...</option>';
                sRayon.disabled = true;

                if (areaId) {
                    fetch(`{{ url('/ajax/rayons') }}/${areaId}`)
                        .then(res => res.json())
                        .then(data => {
                            sRayon.innerHTML = '<option value="">Semua Rayon</option>';
                            data.forEach(rayon => {
                                const option = document.createElement('option');
                                option.value = rayon.id;
                                option.text = rayon.nama;
                                if (rayon.id == currentRayonId) option.selected = true;
                                sRayon.appendChild(option);
                            });
                            sRayon.disabled = false;
                        })
                        .catch(err => {
                            console.error(err);
                            sRayon.innerHTML = '<option value="">Gagal memuat</option>';
                        });
                } else {
                    sRayon.innerHTML = '<option value="">Semua Rayon</option>';
                    sRayon.disabled = true;
                }
            }

            sArea.addEventListener('change', function () {
                loadRayons(this.value);
            });

            if (sArea.value) {
                loadRayons(sArea.value);
            }
        });
    </script>
@endpush