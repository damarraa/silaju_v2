@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Pencarian ID Pelanggan</h2>
        <p class="text-sm text-gray-500">Cari lokasi spesifik PJU berdasarkan Nomor ID Pelanggan (IDPEL).</p>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-5 shadow-default dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('maps.idpel') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3 items-end">

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

            {{-- Filter ID Pelanggan (SEARCH INPUT) --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ID Pelanggan (IDPEL)</label>
                <div class="relative">
                    <input type="text" name="id_pelanggan" value="{{ request('id_pelanggan') }}"
                        placeholder="Contoh: 5123xxxx"
                        class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <span class="absolute right-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                Cari Lokasi
            </button>
        </form>
    </div>

    {{-- MAP CONTAINER --}}
    <div
        class="relative w-full h-[700px] rounded-lg border border-gray-200 shadow-default overflow-hidden dark:border-gray-800">

        {{-- Pesan jika hasil pencarian kosong --}}
        @if(request('id_pelanggan') && count($pjuMarkers) == 0)
            <div class="absolute inset-0 z-20 flex items-center justify-center bg-gray-100/80 backdrop-blur-sm">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-bold text-gray-900">IDPEL Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500">Coba periksa kembali nomor ID Pelanggan atau filter Rayon.</p>
                </div>
            </div>
        @endif

        <div id="map" class="w-full h-full z-0"></div>

        {{-- Custom Legend --}}
        <div
            class="absolute bottom-6 left-4 z-10 bg-white/95 backdrop-blur-sm p-4 rounded-lg shadow-lg border border-gray-200 dark:bg-gray-900/90 dark:border-gray-700 text-xs font-medium">
            <h4 class="font-bold mb-3 text-black dark:text-white uppercase border-b pb-1">Legenda</h4>
            <div class="flex items-center gap-3 mb-2">
                <img src="https://maps.google.com/mapfiles/kml/pal3/icon28.png" class="w-6 h-6">
                <span class="text-gray-700 dark:text-gray-300">Gardu Trafo</span>
            </div>
            <div class="flex items-center gap-3 mb-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#10B981" stroke="white" stroke-width="1.5">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="4" fill="white" fill-opacity="0.3" />
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Baik</span>
            </div>
            <div class="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#EF4444" stroke="white" stroke-width="1.5">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="4" fill="white" fill-opacity="0.3" />
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Rusak</span>
            </div>
        </div>
    </div>

    {{-- GOOGLE MAPS SCRIPT --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async
        defer></script>

    <script>
        let map;
        const pjuData = @json($pjuMarkers);
        const trafoData = @json($trafoMarkers);

        function calculateOffset(lat, lng, index, total) {
            if (total <= 1) return { lat, lng };
            const radius = 0.00015;
            const angle = (360 / total) * index;
            const radians = angle * (Math.PI / 180);
            return { lat: lat + (radius * Math.cos(radians)), lng: lng + (radius * Math.sin(radians)) };
        }

        function initMap() {
            // Center Map Logic: Jika hasil pencarian ada, zoom ke hasil tersebut
            // Jika tidak ada/belum search, default Pekanbaru
            const centerLoc = trafoData.length > 0
                ? { lat: trafoData[0].lat, lng: trafoData[0].lng }
                : { lat: 0.5071, lng: 101.4478 };

            // Zoom Level Logic: Jika mencari ID spesifik (hasil sedikit), Zoom lebih dekat
            const initialZoom = (trafoData.length === 1) ? 19 : 14;

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: initialZoom,
                center: centerLoc,
                mapTypeId: 'roadmap',
                streetViewControl: false,
                mapTypeControl: false,
                fullscreenControl: true
            });

            const infoWindow = new google.maps.InfoWindow();

            // 1. RENDER TRAFO
            trafoData.forEach(trafo => {
                const marker = new google.maps.Marker({
                    position: { lat: trafo.lat, lng: trafo.lng },
                    map: map,
                    icon: {
                        url: "https://maps.google.com/mapfiles/kml/pal3/icon28.png",
                        scaledSize: new google.maps.Size(40, 40),
                        anchor: new google.maps.Point(20, 20)
                    },
                    title: `Gardu: ${trafo.kode}`,
                    zIndex: 1
                });
                marker.addListener("click", () => {
                    infoWindow.setContent(`<div style="padding:5px; text-align:center;"><b>Gardu: ${trafo.kode}</b></div>`);
                    infoWindow.open(map, marker);
                });
            });

            // 2. RENDER PJU
            pjuData.forEach(pju => {
                const offsetPos = calculateOffset(pju.lat, pju.lng, pju.sibling_index, pju.total_siblings);

                let fillColor = "#10B981";
                if (pju.kondisi === 'rusak') fillColor = "#EF4444";
                else if (pju.status === 'ilegal') fillColor = "#F59E0B";

                // Jika ini adalah hasil pencarian (dan cuma 1), kita bisa highlight lebih
                const isHighlight = pjuData.length === 1;

                const marker = new google.maps.Marker({
                    position: offsetPos,
                    map: map,
                    icon: {
                        path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z",
                        fillColor: fillColor,
                        fillOpacity: 1,
                        strokeWeight: 1.5,
                        strokeColor: "#FFFFFF",
                        scale: isHighlight ? 2.5 : 2, // Lebih besar jika hasil pencarian tunggal
                        anchor: new google.maps.Point(12, 22),
                        labelOrigin: new google.maps.Point(12, 9)
                    },
                    title: `IDPEL: ${pju.title}`,
                    label: { text: pju.nomor, color: "white", fontSize: "11px", fontWeight: "bold" },
                    zIndex: 999,
                    animation: isHighlight ? google.maps.Animation.DROP : null // Animasi jatuh jika hasil tunggal
                });

                // Garis Konektor
                new google.maps.Polyline({
                    path: [{ lat: pju.lat, lng: pju.lng }, offsetPos],
                    geodesic: true,
                    strokeColor: "#6B7280",
                    strokeOpacity: 0,
                    strokeWeight: 1.5,
                    icons: [{ icon: { path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 2 }, offset: '0', repeat: '10px' }],
                    map: map,
                    zIndex: 2
                });

                marker.addListener("click", () => {
                    const statusColor = pju.kondisi === 'rusak' ? '#EF4444' : '#10B981';
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

                // Auto-open infowindow jika hasil tunggal
                if (isHighlight) {
                    new google.maps.event.trigger(marker, 'click');
                }
            });
        }
    </script>
@endsection