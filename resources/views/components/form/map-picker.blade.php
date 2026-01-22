@props(['lat' => -0.5071, 'lng' => 101.4478])

<div class="relative overflow-hidden rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 p-1">
    <div id="map" class="h-[250px] w-full z-0 rounded-md"></div>

    <button type="button" id="btn-get-loc"
        class="absolute bottom-3 right-3 z-[400] flex items-center gap-2 rounded-md bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-md border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 transition">
        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
        </svg>
        Lokasi Saya
    </button>
</div>

<div class="grid grid-cols-2 gap-4 mt-3">
    <div>
        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Latitude <span class="text-error-500">*</span></label>
        <input type="text" id="latitude_input" name="latitude" x-model="lat" placeholder="-0.xxxxxx"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
    </div>
    <div>
        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Longitude <span class="text-error-500">*</span></label>
        <input type="text" id="longitude_input" name="longitude" x-model="lng" placeholder="101.xxxxxx"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
    </div>
</div>
<p class="text-[10px] text-gray-400 mt-1 italic">*Geser pin di peta atau ketik koordinat manual.</p>

{{-- ASSETS & SCRIPTS --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            .leaflet-pane {
                z-index: 0 !important;
            }

            /* Fix z-index dropdown conflict */
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // --- CONFIG ---
            // Ambil default value dari props blade atau fallback
            const startLat = {{ $lat }};
            const startLng = {{ $lng }};

            // --- INIT MAP ---
            const map = L.map('map', { scrollWheelZoom: false }).setView([startLat, startLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Fix Marker Icon Missing
            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            });

            const marker = L.marker([startLat, startLng], { draggable: true, autoPan: true }).addTo(map);

            // Fix Render Size di dalam Grid/Flex
            setTimeout(() => { map.invalidateSize(); }, 300);

            // --- ELEMENTS ---
            const latInput = document.getElementById('latitude_input');
            const lngInput = document.getElementById('longitude_input');
            const btnLoc = document.getElementById('btn-get-loc');

            // --- FUNCTIONS ---
            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
                // Dispatch event agar AlpineJS x-model mendeteksi perubahan
                latInput.dispatchEvent(new Event('input'));
                lngInput.dispatchEvent(new Event('input'));
            }

            function updateMap(lat, lng) {
                if (!isNaN(lat) && !isNaN(lng)) {
                    const newPos = new L.LatLng(lat, lng);
                    marker.setLatLng(newPos);
                    map.panTo(newPos);
                }
            }

            // --- LISTENERS (SYNC 2 ARAH) ---

            // 1. Marker Drag -> Input
            marker.on('dragend', function (e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            // 2. Input Manual -> Map
            [latInput, lngInput].forEach(input => {
                input.addEventListener('input', () => {
                    updateMap(parseFloat(latInput.value), parseFloat(lngInput.value));
                });
            });

            // 3. Geolocation
            btnLoc.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert("Browser tidak mendukung GPS"); return;
                }

                const originalText = btnLoc.innerHTML;
                btnLoc.innerHTML = "Mencari...";
                btnLoc.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        updateMap(lat, lng);
                        updateInputs(lat, lng);
                        map.setView([lat, lng], 17);
                        btnLoc.innerHTML = "Ditemukan!";
                        setTimeout(() => { btnLoc.innerHTML = originalText; btnLoc.disabled = false; }, 2000);
                    },
                    (err) => {
                        alert("Gagal mendapatkan lokasi: " + err.message);
                        btnLoc.innerHTML = originalText;
                        btnLoc.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            });
        });
    </script>
@endpush