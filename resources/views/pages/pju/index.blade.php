@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Data Lampu Penerangan Jalan Umum
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Data LPJU</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form method="GET" action="{{ route('pju.index') }}" class="space-y-4">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <div class="relative w-full md:w-1/3">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/></svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Pelanggan / Alamat / Merk..." 
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition"
                        />
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <a href="{{ route('pju.create') }}" class="flex flex-1 md:flex-none items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-sm">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16"><path d="M15 7H9V1C9 0.4 8.6 0 8 0C7.4 0 7 0.4 7 1V7H1C0.4 7 0 7.4 0 8C0 8.6 0.4 9 1 9H7V15C7 15.6 7.4 16 8 16C8.6 16 9 15.6 9 15V9H15C15.6 9 16 8.6 16 8C16 7.4 15.6 7 15 7Z" /></svg>
                            Input Data
                        </a>
                        {{-- Tombol Excel --}}
                        <a href="{{ route('pju.export.excel', request()->query()) }}" target="_blank" 
                        class="flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition shadow-sm"
                        title="Export ke Excel">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20"><path d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z"/></svg>
                            <span class="hidden sm:inline">Excel</span>
                        </a>

                        {{-- Tombol PDF --}}
                        <a href="{{ route('pju.export.pdf', request()->query()) }}" target="_blank" 
                        class="flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition shadow-sm"
                        title="Export ke PDF">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20"><path d="M6 2C4.89543 2 4 2.89543 4 4V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V6L12 2H6Z" stroke="none"/><path d="M11 2V7H16" fill="white" fill-opacity="0.3"/></svg>
                            <span class="hidden sm:inline">PDF</span>
                        </a>
                    </div>
                </div>

                <div class="h-px bg-gray-200 dark:bg-gray-700"></div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="relative">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Filter Area</label>
                        <select name="area_id" id="filter_area_id" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Semua Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Filter Rayon</label>
                        <select name="rayon_id" id="filter_rayon_id" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50">
                            <option value="">Semua Rayon</option>
                        </select>
                    </div>

                    <div class="relative">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Cari ID Gardu</label>
                        {{-- Menggunakan Datalist agar bisa diketik --}}
                        <input list="trafo_list" name="trafo_id_input" 
                            value="{{ $trafos->where('id', request('trafo_id'))->first()->id_gardu ?? '' }}"
                            placeholder="Ketik ID Gardu..."
                            onchange="updateTrafoId(this)"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                        {{-- Hidden input untuk menyimpan ID Gardu sebenarnya --}}
                        <input type="hidden" name="trafo_id" id="trafo_id_hidden" value="{{ request('trafo_id') }}">
                        
                        <datalist id="trafo_list">
                            @foreach($trafos as $trafo)
                                <option data-id="{{ $trafo->id }}" value="{{ $trafo->id_gardu }}">
                                    {{ $trafo->alamat }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <select name="status" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Tipe Meter</option>
                        <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi</option>
                        <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>Non Meter</option>
                        <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                    </select>

                    <select name="kondisi" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>

                    <select name="verification_status" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Status Verifikasi</option>
                        <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

            </form>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto">
                <thead class="border-b border-gray-200 bg-gray-50 text-left dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 w-10 font-medium text-gray-500 text-xs uppercase dark:text-gray-400 text-center">No</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Bukti</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">ID / Lokasi</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Spesifikasi</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Verifikasi</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase text-right dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($pjus as $pju)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <td class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ ($pjus->currentPage() - 1) * $pjus->perPage() + $loop->iteration }}
                        </td>
                        
                        <td class="px-4 py-3">
                            <div class="h-12 w-12 rounded-md overflow-hidden border border-gray-200 dark:border-gray-700 relative group cursor-pointer" onclick="window.open('{{ asset('storage/' . $pju->evidence) }}', '_blank')">
                                @if($pju->evidence)
                                    <img src="{{ asset('storage/' . $pju->evidence) }}" class="h-full w-full object-cover group-hover:scale-110 transition" alt="Bukti">
                                @else
                                    <div class="h-full w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">N/A</div>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ $pju->id_pelanggan ?? '-' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]" title="{{ $pju->alamat }}">
                                    {{ Str::limit($pju->alamat, 35) }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    {{ $pju->kelurahan }}
                                    @if($pju->area) • {{ $pju->area->nama }} @endif
                                </span>
                                {{-- Menampilkan Gardu Asal --}}
                                @if($pju->trafo)
                                    <span class="inline-flex mt-1 items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        GD: {{ $pju->trafo->id_gardu }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700 dark:text-gray-300">
                                <div class="font-medium">{{ $pju->jenis_lampu }} - {{ $pju->watt }}W</div>
                                <div class="text-gray-500">{{ $pju->merk_lampu }}</div>
                                @if($pju->kondisi_lampu == 'rusak')
                                    <span class="text-red-500 font-bold text-[10px] uppercase">Rusak</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($pju->status) {
                                    'meterisasi' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-500',
                                    'non_meterisasi' => 'bg-orange-50 text-orange-600 dark:bg-orange-500/15 dark:text-orange-400',
                                    'ilegal' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-400',
                                    default => 'bg-gray-50 text-gray-600'
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $pju->status)) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $verifyClass = match($pju->verification_status) {
                                    'verified' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                    'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                                    default    => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-500/15 dark:text-yellow-400 dark:border-yellow-500/20',
                                };
                                $verifyLabel = match($pju->verification_status) {
                                    'verified' => 'Approved',
                                    'rejected' => 'Rejected',
                                    default    => 'Pending',
                                };
                            @endphp
                            <span class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $verifyClass }}">
                                {{ $verifyLabel }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pju.edit', $pju->id) }}" class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500">
                                    <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M2.29 16.29L16.29 2.29C16.68 1.9 17.32 1.9 17.71 2.29L21.71 6.29C22.1 6.68 22.1 7.32 21.71 7.71L7.71 21.71C7.32 22.1 6.68 22.1 6.29 21.71L2.29 17.71C1.9 17.32 1.9 16.68 2.29 16.29ZM19.29 7L17 4.71L15 6.71L17.29 9L19.29 7ZM13 8.71L4.71 17L7 19.29L15.29 11L13 8.71Z"/></svg>
                                </a>
                                
                                <form action="{{ route('pju.destroy', $pju->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500">
                                        <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19Z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data PJU ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $pjus->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // === LOGIC 1: HANDLER DATALIST TRAFO (Untuk Filter ID Gardu) ===
    function updateTrafoId(inputElement) {
        const val = inputElement.value;
        const list = document.getElementById('trafo_list');
        const hiddenInput = document.getElementById('trafo_id_hidden');
        const form = inputElement.closest('form');

        // Cari option yang text-nya sesuai input user
        const options = list.options;
        let foundId = '';

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                foundId = options[i].getAttribute('data-id');
                break;
            }
        }

        // Set hidden input value
        hiddenInput.value = foundId;

        // Auto submit form jika ditemukan ID-nya (opsional, bisa hapus baris ini kalau mau manual enter)
        if(foundId) {
            form.submit();
        }
    }

    // === LOGIC 2: LOAD RAYON BERDASARKAN AREA (AJAX) ===
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
                            option.text = rayon.nama; // Pastikan 'nama'
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

        sArea.addEventListener('change', function() {
            loadRayons(this.value);
        });

        if (sArea.value) {
            loadRayons(sArea.value);
        }
    });
</script>
@endpush