@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Peta Wilayah Administratif</h2>
        <p class="text-sm text-gray-500">Visualisasi sebaran PJU berdasarkan Kecamatan dan Kelurahan.</p>
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-5 shadow-default dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('maps.area') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3 items-end">

            {{-- Filter Kecamatan --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kecamatan</label>
                {{-- Tambahkan onchange="this.form.submit()" agar saat pilih kecamatan, kelurahan langsung reload --}}
                <select name="kecamatan" onchange="this.form.submit()"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelurahan (Hanya aktif jika kecamatan dipilih) --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kelurahan</label>
                <select name="kelurahan"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    {{ empty($kelurahans) ? 'disabled' : '' }}>
                    <option value="">-- Pilih Kelurahan --</option>
                    @foreach($kelurahans as $kel)
                        <option value="{{ $kel }}" {{ request('kelurahan') == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
                Tampilkan Peta
            </button>
        </form>
    </div>

    {{-- MAP CONTAINER (SAMA PERSIS) --}}
    <div
        class="relative w-full h-[700px] rounded-lg border border-gray-200 shadow-default overflow-hidden dark:border-gray-800">
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
                    <text x="12" y="13" font-size="10" text-anchor="middle" fill="white" font-weight="bold">1</text>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Baik</span>
            </div>
            <div class="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#EF4444" stroke="white" stroke-width="1.5">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="4" fill="white" fill-opacity="0.3" />
                    <text x="12" y="13" font-size="10" text-anchor="middle" fill="white" font-weight="bold">2</text>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">PJU Rusak</span>
            </div>
        </div>
    </div>

    {{-- GOOGLE MAPS SCRIPT (SAMA PERSIS LOGICNYA) --}}
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
            const centerLoc = trafoData.length > 0
                ? { lat: trafoData[0].lat, lng: trafoData[0].lng }
                : { lat: 0.5071, lng: 101.4478 };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 18,
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

            // 2. RENDER PJU (Dengan Jittering)
            pjuData.forEach(pju => {
                const offsetPos = calculateOffset(pju.lat, pju.lng, pju.sibling_index, pju.total_siblings);

                let fillColor = "#10B981";
                if (pju.kondisi === 'rusak') fillColor = "#EF4444";
                else if (pju.status === 'ilegal') fillColor = "#F59E0B";

                const marker = new google.maps.Marker({
                    position: offsetPos,
                    map: map,
                    icon: {
                        path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z",
                        fillColor: fillColor,
                        fillOpacity: 1,
                        strokeWeight: 1.5,
                        strokeColor: "#FFFFFF",
                        scale: 2,
                        anchor: new google.maps.Point(12, 22),
                        labelOrigin: new google.maps.Point(12, 9)
                    },
                    title: `IDPEL: ${pju.title}`,
                    label: { text: pju.nomor, color: "white", fontSize: "11px", fontWeight: "bold" },
                    zIndex: 999
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

                // InfoWindow Detail (Update sedikit untuk menampilkan Kec/Kel)
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
                                    <div><strong>Kec:</strong> ${pju.kecamatan}</div>
                                    <div><strong>Kel:</strong> ${pju.kelurahan}</div>
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