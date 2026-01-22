@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Galeri Foto PJU
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Galeri PJU</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        {{-- FILTER SECTION --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form method="GET" action="{{ route('pju.gallery') }}" class="space-y-4">

                {{-- Row 1: Search & Export --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative w-full md:w-1/2">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari ID Pelanggan / Alamat..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition" />
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="button"
                            class="flex flex-1 md:flex-none items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition shadow-sm">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20">
                                <path
                                    d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                {{-- Row 2: Detail Filters --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <select name="area_id" id="filter_area_id" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                        <option value="">Semua Area</option>
                        @foreach($areas as $area) <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nama }}</option> @endforeach
                    </select>

                    <select name="rayon_id" id="filter_rayon_id" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400 disabled:opacity-50">
                        <option value="">Semua Rayon</option>
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                        <option value="">Semua Tipe</option>
                        <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi
                        </option>
                        <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>Non Meter
                        </option>
                        <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                    </select>

                    <select name="kondisi" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>

                    <select name="verification_status" onchange="this.form.submit()"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                        <option value="">Semua Status Approval</option>
                        <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>
                            Terverifikasi</option>
                        <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>
                </div>
            </form>
        </div>

        {{-- GALLERY GRID --}}
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($pjus as $pju)
                    <div
                        class="group relative rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-300">

                        {{-- Image Wrapper --}}
                        <div class="relative h-48 w-full overflow-hidden rounded-t-lg bg-gray-100 cursor-pointer"
                            onclick="window.open('{{ asset('storage/' . $pju->evidence) }}', '_blank')">
                            @if($pju->evidence)
                                <img src="{{ asset('storage/' . $pju->evidence) }}"
                                    class="h-full w-full object-cover group-hover:scale-110 transition duration-500" alt="PJU">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-400 text-xs">No Image</div>
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
                                @if($pju->verification_status == 'verified')
                                    <span
                                        class="bg-green-500/90 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">VERIFIED</span>
                                @elseif($pju->verification_status == 'rejected')
                                    <span
                                        class="bg-red-500/90 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">REJECTED</span>
                                @endif

                                @if($pju->kondisi_lampu == 'rusak')
                                    <span
                                        class="bg-red-600/90 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">RUSAK</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1 truncate">
                                {{ $pju->id_pelanggan ?? 'Non-Meter' }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 truncate" title="{{ $pju->alamat }}">
                                {{ $pju->alamat }}
                            </p>

                            <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex gap-1">
                                    <span
                                        class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $pju->daya ?? '-' }} VA
                                    </span>
                                </div>
                                <a href="{{ route('pju.edit', $pju->id) }}"
                                    class="text-xs text-brand-500 hover:underline flex items-center gap-1">
                                    Detail <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        <div class="mb-2">
                            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p>Tidak ada foto ditemukan dengan filter ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $pjus->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // AJAX Rayon Filter
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

            sArea.addEventListener('change', function () { loadRayons(this.value); });
            if (sArea.value) { loadRayons(sArea.value); }
        });
    </script>
@endpush