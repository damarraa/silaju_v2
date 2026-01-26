@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        
        {{-- SECTION 1: KEY METRICS (Adaptasi dari ecommerce-metrics) --}}
        <div class="col-span-12 space-y-6 xl:col-span-12">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
                
                {{-- CARD 1: TOTAL PJU --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            {{-- Icon Lampu --}}
                            <svg class="fill-gray-600 dark:fill-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total PJU</span>
                            <h4 class="text-title-md font-bold text-gray-800 dark:text-white">{{ number_format($totalPju) }}</h4>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: TOTAL TRAFO --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/20">
                            {{-- Icon Listrik --}}
                            <svg class="fill-blue-600 dark:fill-blue-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.28-.62l8-13h2l-1 7h3.5c.45 0 .54.23.33.52l-9 13.1z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trafo</span>
                            <h4 class="text-title-md font-bold text-gray-800 dark:text-white">{{ number_format($totalTrafo) }}</h4>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: KONDISI RUSAK --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-50 dark:bg-red-900/20">
                            {{-- Icon Alert --}}
                            <svg class="fill-red-600 dark:fill-red-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">PJU Rusak</span>
                            <div class="flex items-end gap-2">
                                <h4 class="text-title-md font-bold text-gray-800 dark:text-white">{{ number_format($pjuRusak) }}</h4>
                                <span class="text-sm font-medium text-red-500 mb-1">({{ $persenRusak }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 4: ILEGAL --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-900/20">
                            {{-- Icon Warning --}}
                            <svg class="fill-orange-600 dark:fill-orange-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Sambungan Ilegal</span>
                            <h4 class="text-title-md font-bold text-gray-800 dark:text-white">{{ number_format($pjuIlegal) }}</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- SECTION 2: CHARTS --}}
        
        {{-- Chart Sebaran Rayon (Adaptasi monthly-sale) --}}
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 pb-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistik Kerusakan per Rayon</h3>
                </div>
                <div id="chartRayon" class="-ml-5 h-[350px] w-full"></div>
            </div>
        </div>

        {{-- Chart Status Meterisasi (Adaptasi monthly-target) --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 pb-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Komposisi Meterisasi</h3>
                </div>
                <div id="chartStatus" class="flex justify-center h-[350px]"></div>
            </div>
        </div>

        {{-- SECTION 3: RECENT TABLE (Adaptasi recent-orders) --}}
        <div class="col-span-12">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
                <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Input Data Terbaru</h3>
                    <a href="{{ route('pju.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">Lihat Semua</a>
                </div>
                
                <div class="max-w-full overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-100 dark:border-gray-800">
                            <tr>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">Rayon</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">Status</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">Kondisi</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">Petugas</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPjus as $item)
                            <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-white">{{ $item->id_pelanggan }}</td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $item->rayon->nama ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $item->status == 'ilegal' ? 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500' : 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-500' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $item->kondisi_lampu == 'rusak' ? 'bg-orange-50 text-orange-600 dark:bg-orange-500/15 dark:text-orange-500' : 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500' }}">
                                        {{ ucfirst($item->kondisi_lampu) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $item->user->name ?? 'System' }}</td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- APEXCHARTS SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- CHART 1: BAR CHART RAYON ---
            const rayonOptions = {
                series: [{
                    name: 'Baik',
                    data: @json($chartRayonBaik)
                }, {
                    name: 'Rusak',
                    data: @json($chartRayonRusak)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: @json($chartRayonLabels),
                },
                colors: ['#10B981', '#EF4444'], // Green, Red
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Unit"
                        }
                    }
                },
                legend: { position: 'top' }
            };

            const chartRayon = new ApexCharts(document.querySelector("#chartRayon"), rayonOptions);
            chartRayon.render();

            // --- CHART 2: DONUT CHART STATUS ---
            const statusOptions = {
                series: @json($chartStatusData), // [Meterisasi, Non, Ilegal]
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Inter, sans-serif'
                },
                labels: ['Meterisasi', 'Non-Meterisasi', 'Ilegal'],
                colors: ['#3C50E0', '#80CAEE', '#F0950C'], // Brand colors adaptation
                legend: { position: 'bottom' },
                dataLabels: { enabled: true },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%'
                        }
                    }
                }
            };

            const chartStatus = new ApexCharts(document.querySelector("#chartStatus"), statusOptions);
            chartStatus.render();
        });
    </script>
@endsection