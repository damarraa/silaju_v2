@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Rekapitulasi Detail Aset</h2>
            <p class="text-sm text-gray-500 mt-1">Rincian jenis lampu dan daya per Gardu.</p>
        </div>
        
        <div class="flex gap-2">
            <button class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20"><path d="M14.333 11.267c0 .267.133.533.333.733l2 2c.4.4 1.067.4 1.467 0 .4-.4.4-1.067 0-1.467l-.267-.266V2.667c0-.534-.466-1-1-1s-1 .466-1 1v9.6ZM13.067 16H2.933c-.533 0-1-.467-1-1V5c0-.533.467-1 1-1h6.2c.533 0 1-.467 1-1s-.467-1-1-1h-6.2c-1.667 0-3 1.333-3 3v10c0 1.667 1.333 3 3 3h10.133c1.667 0 3-1.333 3-3v-4.133c0-.534-.467-1-1-1s-1 .466-1 1V15c0 .533-.467 1-1 1Z"/></svg>
                Excel
            </button>
        </div>
    </div>

    {{-- FILTER BAR (Copy Paste dari Realisasi - Grid 5 Kolom) --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-5 shadow-default dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('pju.rekap.jenis') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Gardu / Alamat..." class="rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                
                <select name="rayon_id" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Rayon</option>
                    @foreach($rayons as $r) <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option> @endforeach
                </select>

                <select name="kabupaten" onchange="resetKecamatan(); this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Kabupaten</option>
                    @foreach($kabupatens as $kab) <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>{{ $kab }}</option> @endforeach
                </select>

                <select name="kecamatan" onchange="resetKelurahan(); this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50" {{ empty($kecamatans) ? 'disabled' : '' }}>
                    <option value="">{{ empty($kecamatans) ? 'Pilih Kab Dulu' : 'Semua Kecamatan' }}</option>
                    @foreach($kecamatans as $kec) <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option> @endforeach
                </select>

                <select name="kelurahan" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-50" {{ empty($kelurahans) ? 'disabled' : '' }}>
                    <option value="">{{ empty($kelurahans) ? 'Pilih Kec Dulu' : 'Semua Kelurahan' }}</option>
                    @foreach($kelurahans as $kel) <option value="{{ $kel }}" {{ request('kelurahan') == $kel ? 'selected' : '' }}>{{ $kel }}</option> @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- LOOPING TRAFO (Card Style per Trafo) --}}
    <div class="flex flex-col gap-6">
        @forelse($trafos as $trafo)
            <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                {{-- Header Trafo --}}
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-black dark:text-white font-mono flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                {{ $trafo->id_gardu }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $trafo->alamat }} ({{ $trafo->kelurahan }})</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                Total: {{ $trafo->pjus->count() }} Titik
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white dark:bg-gray-900 text-gray-500 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 font-medium">Status & Jenis Lampu</th>
                                <th class="px-6 py-3 font-medium text-right">Daya (Watt)</th>
                                <th class="px-6 py-3 font-medium text-right">Jumlah Titik</th>
                                <th class="px-6 py-3 font-medium text-right">Total Beban</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            
                            {{-- LOGIC GROUPING DI VIEW (Meterisasi vs Non Meter) --}}
                            @php
                                $groups = [
                                    'meterisasi' => ['label' => 'METERISASI', 'color' => 'text-blue-600 bg-blue-50', 'data' => $trafo->pjus->where('status', 'meterisasi')],
                                    'non_meterisasi' => ['label' => 'NON-METERISASI', 'color' => 'text-orange-600 bg-orange-50', 'data' => $trafo->pjus->where('status', 'non_meterisasi')],
                                    'ilegal' => ['label' => 'ILEGAL', 'color' => 'text-red-600 bg-red-50', 'data' => $trafo->pjus->where('status', 'ilegal')], // Jaga-jaga ada status lain
                                ];
                            @endphp

                            @foreach($groups as $key => $group)
                                @if($group['data']->isNotEmpty())
                                    {{-- Sub Header Status --}}
                                    <tr class="{{ $group['color'] }} dark:bg-opacity-10">
                                        <td colspan="4" class="px-6 py-2 font-bold text-xs tracking-wider uppercase border-t border-b border-gray-100 dark:border-gray-700">
                                            {{ $group['label'] }}
                                        </td>
                                    </tr>

                                    {{-- Grouping per Jenis Lampu & Watt --}}
                                    @php
                                        // Group by Jenis Lampu + Watt agar unik
                                        $lampuGroups = $group['data']->groupBy(function($item) {
                                            return $item->jenis_lampu . ' - ' . $item->watt;
                                        });
                                    @endphp

                                    @foreach($lampuGroups as $spec => $items)
                                        @php 
                                            $first = $items->first(); 
                                            $totalWatt = $items->sum('watt');
                                            $totalCount = $items->count();
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-gray-300">
                                            <td class="px-6 py-3 pl-8">
                                                {{ $first->jenis_lampu }}
                                                @if($first->kondisi_lampu == 'rusak')
                                                     <span class="ml-2 text-[10px] text-red-500 border border-red-200 px-1 rounded">RUSAK ({{ $items->where('kondisi_lampu','rusak')->count() }})</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3 text-right font-mono">{{ $first->watt }} W</td>
                                            <td class="px-6 py-3 text-right font-bold">{{ $totalCount }}</td>
                                            <td class="px-6 py-3 text-right font-mono text-brand-600 dark:text-brand-400">
                                                {{ number_format($totalWatt) }} W
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    {{-- Subtotal per Status (Opsional, tapi diminta user ada total di ujung) --}}
                                    <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 font-medium text-xs text-gray-500">
                                        <td colspan="2" class="px-6 py-2 text-right uppercase">Subtotal {{ $group['label'] }}</td>
                                        <td class="px-6 py-2 text-right">{{ $group['data']->count() }}</td>
                                        <td class="px-6 py-2 text-right">{{ number_format($group['data']->sum('watt')) }} W</td>
                                    </tr>
                                @endif
                            @endforeach

                            {{-- Grand Total Trafo --}}
                            <tr class="bg-gray-100 dark:bg-gray-800 border-t-2 border-gray-200 dark:border-gray-600 font-bold text-black dark:text-white">
                                <td colspan="2" class="px-6 py-3 text-right uppercase">Total Keseluruhan</td>
                                <td class="px-6 py-3 text-right">{{ $trafo->pjus->count() }}</td>
                                <td class="px-6 py-3 text-right">{{ number_format($trafo->pjus->sum('watt')) }} W</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-500 dark:border-gray-800 dark:bg-gray-900">
                <p>Data tidak ditemukan. Coba ubah filter pencarian.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $trafos->links() }}
    </div>

    <script>
        function resetKecamatan() { document.querySelector('select[name="kecamatan"]').value = ""; document.querySelector('select[name="kelurahan"]').value = ""; }
        function resetKelurahan() { document.querySelector('select[name="kelurahan"]').value = ""; }
    </script>
@endsection