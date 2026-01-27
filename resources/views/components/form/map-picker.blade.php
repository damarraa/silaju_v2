@props(['lat' => -0.5071, 'lng' => 101.4478])

<div class="relative overflow-hidden rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 p-1">
    {{-- Container Map --}}
    <div id="map" class="h-[300px] w-full z-0 rounded-md"></div>

    {{-- Dropdown Map Type (Posisi Kiri Atas) --}}
    <select id="map-type-selector"
        class="absolute top-3 left-3 z-10 rounded-md bg-white border border-gray-200 py-1.5 pl-3 pr-8 text-xs font-bold text-gray-700 shadow-lg focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:text-white dark:border-gray-600 cursor-pointer">
        <option value="roadmap">Roadmap</option>
        <option value="satellite">Satelit</option>
        <option value="hybrid">Hybrid</option>
        <option value="terrain">Terrain</option>
    </select>

    {{-- Tombol Lokasi Saya (Posisi Kiri Bawah) --}}
    <button type="button" id="btn-get-loc"
        class="absolute bottom-4 left-3 z-10 flex items-center gap-2 rounded-md bg-white px-3 py-2 text-xs font-bold text-gray-700 shadow-lg border border-gray-200 hover:bg-gray-50 active:scale-95 transition dark:bg-gray-800 dark:text-white dark:border-gray-600">
        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
        </svg>
        <span>Lokasi Saya</span>
    </button>
</div>

{{-- Input Koordinat --}}
<div class="grid grid-cols-2 gap-4 mt-3">
    <div>
        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Latitude <span
                class="text-error-500">*</span></label>
        <input type="text" id="latitude_input" name="latitude" x-model="lat" placeholder="-0.xxxxxx"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition" />
    </div>
    <div>
        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Longitude <span
                class="text-error-500">*</span></label>
        <input type="text" id="longitude_input" name="longitude" x-model="lng" placeholder="101.xxxxxx"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition" />
    </div>
</div>
<p class="text-[10px] text-gray-400 mt-1 italic">*Geser pin atau <strong>klik pada peta</strong> untuk menentukan titik.
</p>

@push('scripts')
    {{-- Pastikan API KEY ada di .env --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async
        defer></script>

    <script>
        let map, marker;
        let currentLat = {{ $lat }};
        let currentLng = {{ $lng }};

        const latInput = document.getElementById('latitude_input');
        const lngInput = document.getElementById('longitude_input');
        const btnLoc = document.getElementById('btn-get-loc');
        const mapTypeSelector = document.getElementById('map-type-selector');

        function initMap() {
            const myLatLng = { lat: currentLat, lng: currentLng };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
                mapTypeId: 'roadmap',

                disableDefaultUI: true,
                zoomControl: true,       // Tombol +/- (Pojok Kanan Bawah)
                fullscreenControl: true, // Tombol Fullscreen (Pojok Kanan Atas)
                mapTypeControl: false,   // Matikan tombol Map/Satellite bawaan (Ganti pakai dropdown kita)
                streetViewControl: false,// Matikan Pegman (Orang Kuning)
                cameraControl: false,    // Matikan kontrol kemiringan/rotasi (MapCameraControl)
                rotateControl: false,    // Matikan kontrol putar
                scaleControl: false,     // Matikan penggaris skala
                clickableIcons: false // Pastikan Fullscreen mati
            });

            // Marker Inisialisasi
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                draggable: true,
                title: "Titik Lokasi",
                animation: google.maps.Animation.DROP
            });

            // --- EVENT LISTENERS ---

            // A. Click to Move
            map.addListener("click", (mapsMouseEvent) => {
                const newPos = mapsMouseEvent.latLng;
                marker.setPosition(newPos);
                updateInputs(newPos.lat(), newPos.lng());
            });

            // B. Marker Drag End
            marker.addListener("dragend", () => {
                const position = marker.getPosition();
                updateInputs(position.lat(), position.lng());
            });

            // C. Input Manual Change
            [latInput, lngInput].forEach(input => {
                input.addEventListener('input', () => {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const newPos = { lat: lat, lng: lng };
                        marker.setPosition(newPos);
                        map.panTo(newPos);
                    }
                });
            });

            // D. Tombol Lokasi Saya
            btnLoc.addEventListener('click', () => {
                getLocation();
            });

            // E. Map Type Change
            mapTypeSelector.addEventListener('change', function () {
                map.setMapTypeId(this.value);
            });
        }

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            latInput.dispatchEvent(new Event('input'));
            lngInput.dispatchEvent(new Event('input'));
        }

        function getLocation() {
            if (navigator.geolocation) {
                const originalText = btnLoc.innerHTML;
                btnLoc.innerHTML = `<span class="animate-pulse">Mencari...</span>`;
                btnLoc.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        marker.setPosition(pos);
                        map.setCenter(pos);
                        map.setZoom(17);
                        updateInputs(pos.lat, pos.lng);

                        btnLoc.innerHTML = `<span class="text-green-600">Ditemukan!</span>`;
                        setTimeout(() => {
                            btnLoc.innerHTML = originalText;
                            btnLoc.disabled = false;
                        }, 2000);
                    },
                    () => {
                        handleLocationError(true);
                        btnLoc.innerHTML = originalText;
                        btnLoc.disabled = false;
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                handleLocationError(false);
            }
        }

        function handleLocationError(browserHasGeolocation) {
            alert(browserHasGeolocation
                ? "Error: Gagal mendapatkan lokasi (Cek izin GPS browser)."
                : "Error: Browser Anda tidak mendukung Geolocation.");
        }
    </script>
@endpush