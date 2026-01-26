@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Peta Sebaran PJU</h2>
        <p class="text-sm text-gray-500">Visualisasi lokasi titik lampu dan gardu induk.</p>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-5 shadow-default dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('maps.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4 items-end">

            {{-- Filter Rayon --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Rayon</label>
                <select name="rayon_id"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Semua Rayon</option>
                    @foreach($rayons as $r)
                        <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Status Meter</label>
                <select name="status"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="meterisasi" {{ request('status') == 'meterisasi' ? 'selected' : '' }}>Meterisasi</option>
                    <option value="non_meterisasi" {{ request('status') == 'non_meterisasi' ? 'selected' : '' }}>
                        Non-Meterisasi</option>
                    <option value="ilegal" {{ request('status') == 'ilegal' ? 'selected' : '' }}>Ilegal</option>
                </select>
            </div>

            {{-- Filter Kondisi --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kondisi Fisik</label>
                <select name="kondisi_lampu"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi_lampu') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('kondisi_lampu') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- MAP CONTAINER --}}
    <div
        class="relative w-full h-[700px] rounded-lg border border-gray-200 shadow-default overflow-hidden dark:border-gray-800">

        <div id="map" class="w-full h-full z-0"></div>

        {{-- [NEW] Map Type Selector (Floating Top Right) --}}
        <div class="absolute top-4 right-16 z-10">
            <select id="map-type-selector"
                class="rounded-md bg-white border border-gray-200 py-2 pl-3 pr-8 text-xs font-bold text-gray-700 shadow-lg focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:text-white dark:border-gray-600 cursor-pointer transition hover:bg-gray-50">
                <option value="roadmap">Roadmap</option>
                <option value="satellite">Satelit</option>
                <option value="hybrid">Hybrid</option>
                <option value="terrain">Terrain</option>
            </select>
        </div>

        {{-- Custom Legend --}}
        <div
            class="absolute bottom-6 left-4 z-10 bg-white/95 backdrop-blur-sm p-4 rounded-lg shadow-lg border border-gray-200 dark:bg-gray-900/90 dark:border-gray-700 text-xs font-medium">
            <h4 class="font-bold mb-3 text-black dark:text-white uppercase border-b pb-1">Legenda Peta</h4>

            <div class="flex items-center gap-3 mb-2">
                <img src="https://maps.google.com/mapfiles/kml/pal3/icon28.png" class="w-6 h-6">
                <span class="text-gray-700 dark:text-gray-300">Gardu Trafo</span>
            </div>

            <div class="flex items-center gap-3 mb-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#10B981" stroke="white" stroke-width="1.5"
                    style="filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.3));">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="4" fill="white" fill-opacity="0.3" />
                    <text x="12" y="13" font-size="10" text-anchor="middle" fill="white" font-weight="bold">1</text>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Kondisi Baik</span>
            </div>

            <div class="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#EF4444" stroke="white" stroke-width="1.5"
                    style="filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.3));">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="4" fill="white" fill-opacity="0.3" />
                    <text x="12" y="13" font-size="10" text-anchor="middle" fill="white" font-weight="bold">2</text>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Kondisi Rusak</span>
            </div>
        </div>
    </div>

    {{-- GOOGLE MAPS SCRIPT --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async
        defer></script>

    <script>
        let map;
        // Data dari Controller
        const pjuData = @json($pjuMarkers);
        const trafoData = @json($trafoMarkers);
        const mapTypeSelector = document.getElementById('map-type-selector'); // Selector Element

        /**
         * FUNGSI HITUNG OFFSET (Jittering)
         */
        function calculateOffset(lat, lng, index, total) {
            if (total <= 1) return { lat, lng };

            const radius = 0.00015;
            const angle = (360 / total) * index;
            const radians = angle * (Math.PI / 180);

            const newLat = lat + (radius * Math.cos(radians));
            const newLng = lng + (radius * Math.sin(radians));

            return { lat: newLat, lng: newLng };
        }

        function initMap() {
            // 1. Center Map Logic
            const centerLoc = trafoData.length > 0
                ? { lat: trafoData[0].lat, lng: trafoData[0].lng }
                : { lat: 0.5071, lng: 101.4478 };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 18,
                center: centerLoc,
                mapTypeId: 'roadmap', // Default awal
                streetViewControl: false,
                mapTypeControl: false, // Kita pakai custom control sendiri
                fullscreenControl: true
            });

            const infoWindow = new google.maps.InfoWindow();

            // [NEW] Event Listener untuk Ganti Tipe Peta
            mapTypeSelector.addEventListener('change', function () {
                map.setMapTypeId(this.value);
            });

            // 2. RENDER MARKER TRAFO (GARDU)
            trafoData.forEach(trafo => {
                const trafoIcon = {
                    url: "https://maps.google.com/mapfiles/kml/pal3/icon28.png",
                    scaledSize: new google.maps.Size(40, 40),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(20, 20)
                };

                const marker = new google.maps.Marker({
                    position: { lat: trafo.lat, lng: trafo.lng },
                    map: map,
                    icon: trafoIcon,
                    title: `Gardu: ${trafo.kode}`,
                    zIndex: 1
                });

                marker.addListener("click", () => {
                    infoWindow.setContent(`
                                    <div style="padding:5px; text-align:center; min-width:100px;">
                                        <h3 style="font-weight:bold; color:#1a73e8; margin-bottom:5px;">⚡ GARDU</h3>
                                        <div style="font-size:14px;"><strong>${trafo.kode}</strong></div>
                                    </div>
                                `);
                    infoWindow.open(map, marker);
                });
            });

            // 3. RENDER MARKER PJU (ANAK)
            pjuData.forEach(pju => {
                const offsetPos = calculateOffset(pju.lat, pju.lng, pju.sibling_index, pju.total_siblings);

                let fillColor = "#10B981"; // Hijau
                if (pju.kondisi === 'rusak') fillColor = "#EF4444"; // Merah
                else if (pju.status === 'ilegal') fillColor = "#F59E0B"; // Orange

                const pinSVG = {
                    path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z",
                    fillColor: fillColor,
                    fillOpacity: 1,
                    strokeWeight: 1.5,
                    strokeColor: "#FFFFFF",
                    scale: 2,
                    anchor: new google.maps.Point(12, 22),
                    labelOrigin: new google.maps.Point(12, 9)
                };

                const marker = new google.maps.Marker({
                    position: offsetPos,
                    map: map,
                    icon: pinSVG,
                    title: `IDPEL: ${pju.title}`,
                    label: {
                        text: pju.nomor,
                        color: "white",
                        fontSize: "11px",
                        fontWeight: "bold"
                    },
                    zIndex: 999
                });

                // Garis Penghubung
                const connectionLine = new google.maps.Polyline({
                    path: [
                        { lat: pju.lat, lng: pju.lng },
                        offsetPos
                    ],
                    geodesic: true,
                    strokeColor: "#6B7280",
                    strokeOpacity: 0,
                    strokeWeight: 1.5,
                    icons: [{
                        icon: { path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 2 },
                        offset: '0',
                        repeat: '10px'
                    }],
                    map: map,
                    zIndex: 2
                });

                marker.addListener("click", () => {
                    const contentString = `
                                    <div style="min-width:180px; padding:5px; font-family: sans-serif;">
                                        <div style="border-bottom:1px solid #eee; padding-bottom:8px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                                            <strong style="font-size:14px;">💡 PJU No. ${pju.nomor}</strong>
                                            <span style="font-size:10px; background:${fillColor}; color:white; padding:2px 6px; border-radius:10px;">${pju.kondisi.toUpperCase()}</span>
                                        </div>
                                        <div style="font-size:12px; line-height:1.6; color:#444;">
                                            <div><strong>IDPEL:</strong> ${pju.title}</div>
                                            <div><strong>Gardu:</strong> ${pju.trafo_kode}</div>
                                            <div><strong>Rayon:</strong> ${pju.rayon}</div>
                                        </div>
                                        <div style="margin-top:10px; text-align:right;">
                                            <a href="/pju/${pju.id}/edit" target="_blank" style="background:#3B82F6; color:white; padding:5px 10px; text-decoration:none; border-radius:4px; font-size:11px; font-weight:bold;">Lihat Detail</a>
                                        </div>
                                    </div>
                                `;
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                });
            });
        }
    </script>
@endsection