@extends('layouts.app')

@section('content')
    {{-- CSS Leaflet --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Input Data Lapangan
        </h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        <form action="{{ route('pju.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-6.5" x-data="{ 
                                        statusPJU: '{{ old('status') }}',
                                        lat: '{{ old('latitude') }}',
                                        lng: '{{ old('longitude') }}',
                                        previewImage: null,
                                        handleFileChange(event) {
                                            const file = event.target.files[0];
                                            if (file) {
                                                this.previewImage = URL.createObjectURL(file);
                                            }
                                        }
                                    }">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="flex flex-col gap-6">

                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            1. Foto Bukti Lapangan <span class="text-error-500">*</span>
                        </label>
                        <div class="relative w-full">
                            <input type="file" name="evidence" id="evidence" accept="image/*" capture="environment"
                                @change="handleFileChange"
                                class="absolute inset-0 z-50 h-full w-full opacity-0 cursor-pointer" />

                            <div class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition dark:bg-gray-900 dark:border-gray-600 dark:hover:border-gray-500"
                                :class="previewImage ? 'border-brand-500' : 'border-gray-300'">

                                <div x-show="!previewImage"
                                    class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <div class="mb-3 p-2 rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/20">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500">Tap Kamera</p>
                                </div>

                                <img x-show="previewImage" :src="previewImage"
                                    class="absolute inset-0 w-full h-full object-cover rounded-lg" />
                            </div>
                        </div>
                        @error('evidence') <span class="text-xs text-error-500 mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            2. Titik Koordinat
                        </label>

                        <div
                            class="relative overflow-hidden rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100">
                            <div id="map" class="h-[200px] w-full z-0"></div>

                            <button type="button" id="btn-get-loc"
                                class="absolute bottom-2 right-2 z-[400] flex items-center gap-2 rounded-md bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-md border border-gray-200 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Lokasi Saya
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Latitude</label>
                                <input type="text" id="latitude_input" name="latitude" x-model="lat" placeholder="0.000000"
                                    class="w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-xs font-medium text-gray-600 focus:border-brand-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Longitude</label>
                                <input type="text" id="longitude_input" name="longitude" x-model="lng"
                                    placeholder="0.000000"
                                    class="w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-xs font-medium text-gray-600 focus:border-brand-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">PROVINSI</label>
                                <select id="provinsi" name="provinsi"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="">Memuat...</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KAB/KOTA</label>
                                <select id="kabupaten" name="kabupaten" disabled
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    <option value="">-</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KECAMATAN</label>
                                <select id="kecamatan" name="kecamatan" disabled
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    <option value="">-</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KELURAHAN</label>
                                <select id="kelurahan" name="kelurahan" disabled
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    <option value="">-</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">DETAIL ALAMAT</label>
                            <textarea name="alamat" rows="2" placeholder="Nama Jalan, Gg, No. Rumah"
                                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="flex flex-col gap-6">

                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            3. Identitas PJU
                        </label>

                        <div class="space-y-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Meter</label>
                                <select name="status" x-model="statusPJU"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="">Pilih Status</option>
                                    <option value="meterisasi">Meterisasi (Ada KWH)</option>
                                    <option value="non_meterisasi">Non Meterisasi (Abodemen)</option>
                                    <option value="ilegal">Ilegal / Liar</option>
                                </select>
                            </div>

                            <div x-show="statusPJU === 'meterisasi'" x-transition
                                class="grid grid-cols-2 gap-4 border-t pt-4 border-dashed border-gray-200 dark:border-gray-700">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500">ID Pelanggan</label>
                                    <input type="number" name="id_pelanggan" value="{{ old('id_pelanggan') }}"
                                        placeholder="12 Digit"
                                        class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500">Daya (VA)</label>
                                    <select name="daya"
                                        class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                        <option value="">-</option>
                                        <option value="450">450</option>
                                        <option value="900">900</option>
                                        <option value="1300">1300</option>
                                        <option value="2200">2200</option>
                                        <option value="3500">3500</option>
                                        <option value="5500">5500</option>
                                        <option value=">5500">> 5500</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            4. Spesifikasi Teknis
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Lampu</label>
                                <input list="jenis-list" name="jenis_lampu"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white"
                                    placeholder="Ketik/Pilih...">
                                <datalist id="jenis-list">
                                    <option value="LED">
                                    <option value="SON-T">
                                    <option value="MERCURY">
                                    <option value="LHE">
                                    <option value="SOLAR CELL">
                                </datalist>
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Merk</label>
                                <input list="merk-list" name="merk_lampu"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white"
                                    placeholder="Ketik/Pilih...">
                                <datalist id="merk-list">
                                    <option value="PHILIPS">
                                    <option value="OSRAM">
                                    <option value="PANASONIC">
                                    <option value="HANNOCS">
                                    <option value="CHINA BRAND">
                                </datalist>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah
                                    Titik</label>
                                <input type="number" name="jumlah_lampu" value="1"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Watt
                                    (Total)</label>
                                <input type="number" name="watt" placeholder="Contoh: 250"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            5. Operasional
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Kondisi</label>
                                <select name="kondisi_lampu"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Tindak Lanjut</label>
                                <select name="tindak_lanjut"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="dibiarkan">Dibiarkan</option>
                                    <option value="bongkar">Bongkar</option>
                                    <option value="putus">Putus Jaringan</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Panel Kontrol</label>
                                <select name="sistem_operasi"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="photo_cell">Photo Cell</option>
                                    <option value="timer">Timer</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Installasi</label>
                                <select name="installasi"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="kabel_udara">Kabel Udara</option>
                                    <option value="kabel_tanah">Kabel Tanah</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Kepemilikan</label>
                                <select name="kepemilikan"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="pemda">PEMDA</option>
                                    <option value="swadaya">Swadaya</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Peruntukan</label>
                                <select name="peruntukan"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="jalan">Jalan Umum</option>
                                    <option value="taman">Taman</option>
                                    <option value="fasilitas_umum">Fasilitas Umum</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Menyala Siang Hari?</label>
                                <select name="nyala_siang"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="0">Tidak (Normal)</option>
                                    <option value="1">Ya (Menyala Terus)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div
                class="mt-8 flex justify-end gap-3 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 border-t border-gray-200 dark:bg-gray-900/95 dark:border-gray-800 -mx-4 md:static md:bg-transparent md:p-0 md:border-none md:mx-0 z-50">
                <a href="{{ route('pju.index') }}"
                    class="flex-1 md:flex-none items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 md:flex-none items-center justify-center rounded-lg bg-brand-500 px-6 py-3 text-center text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 focus:ring-4 focus:ring-brand-500/20">
                    Simpan Data
                </button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /* =====================================================
             * 1. INIT MAP & MARKER
             * ===================================================== */

            const defaultLat = parseFloat("{{ old('latitude', '-0.5071') }}");
            const defaultLng = parseFloat("{{ old('longitude', '101.4478') }}");

            const map = L.map('map', {
                scrollWheelZoom: false
            }).setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true,
                autoPan: true
            }).addTo(map);

            // FIX TailAdmin Grid / Hidden render
            setTimeout(() => {
                map.invalidateSize();
                marker.setLatLng([defaultLat, defaultLng]);
            }, 300);

            /* =====================================================
             * 2. INPUT ELEMENT
             * ===================================================== */

            const latInput = document.getElementById('latitude_input');
            const lngInput = document.getElementById('longitude_input');

            function syncInput(lat, lng) {
                const latVal = lat.toFixed(6);
                const lngVal = lng.toFixed(6);

                latInput.value = latVal;
                lngInput.value = lngVal;

                // Alpine-safe update (NO bubbling, NO re-render)
                if (latInput._x_model) latInput._x_model.set(latVal);
                if (lngInput._x_model) lngInput._x_model.set(lngVal);
            }

            function setLatLng(lat, lng, zoom = null) {
                marker.setLatLng([lat, lng]).addTo(map);
                map.panTo([lat, lng]);
                if (zoom) map.setZoom(zoom);
                syncInput(lat, lng);
            }

            // Init sync
            syncInput(defaultLat, defaultLng);

            /* =====================================================
             * 3. MARKER ➜ INPUT (DRAG)
             * ===================================================== */

            marker.on('dragend', function () {
                const pos = marker.getLatLng();
                syncInput(pos.lat, pos.lng);
            });

            /* =====================================================
             * 4. INPUT ➜ MAP (MANUAL EDIT)
             * ===================================================== */

            function updateFromInput() {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);

                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.panTo([lat, lng]);
                }
            }

            latInput.addEventListener('change', updateFromInput);
            lngInput.addEventListener('change', updateFromInput);

            /* =====================================================
             * 5. GEOLOCATION BUTTON
             * ===================================================== */

            const btnLoc = document.getElementById('btn-get-loc');

            btnLoc.addEventListener('click', function () {

                if (!navigator.geolocation) {
                    alert("Browser tidak mendukung GPS");
                    return;
                }

                btnLoc.disabled = true;
                btnLoc.innerHTML = "Mencari...";

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;

                        setLatLng(lat, lng, 17);

                        btnLoc.innerHTML = `
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ditemukan
                        `;
                        btnLoc.disabled = false;
                    },
                    () => {
                        alert("Gagal mendapatkan lokasi GPS");
                        btnLoc.innerHTML = "Lokasi Saya";
                        btnLoc.disabled = false;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000
                    }
                );
            });

            /* =====================================================
             * 6. API WILAYAH (TIDAK DIUBAH)
             * ===================================================== */

            const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";
            const sProv = document.getElementById('provinsi');
            const sKab = document.getElementById('kabupaten');
            const sKec = document.getElementById('kecamatan');
            const sKel = document.getElementById('kelurahan');

            async function fetchApi(ep) {
                try {
                    return (await fetch(`${baseUrl}/${ep}.json`)).json();
                } catch {
                    return [];
                }
            }

            function fillSelect(el, data, placeholder) {
                el.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.name;
                    opt.dataset.id = item.id;
                    opt.textContent = item.name;
                    el.appendChild(opt);
                });
                el.disabled = false;
            }

            fetchApi('provinces').then(d => fillSelect(sProv, d, "Pilih Provinsi"));

            sProv.addEventListener('change', function () {
                sKab.innerHTML = '<option>Loading...</option>';
                sKab.disabled = true;
                sKec.disabled = true;
                sKel.disabled = true;

                const id = this.options[this.selectedIndex]?.dataset.id;
                if (id) fetchApi(`regencies/${id}`).then(d => fillSelect(sKab, d, "Pilih Kab/Kota"));
            });

            sKab.addEventListener('change', function () {
                sKec.innerHTML = '<option>Loading...</option>';
                sKec.disabled = true;
                sKel.disabled = true;

                const id = this.options[this.selectedIndex]?.dataset.id;
                if (id) fetchApi(`districts/${id}`).then(d => fillSelect(sKec, d, "Pilih Kecamatan"));
            });

            sKec.addEventListener('change', function () {
                sKel.innerHTML = '<option>Loading...</option>';
                sKel.disabled = true;

                const id = this.options[this.selectedIndex]?.dataset.id;
                if (id) fetchApi(`villages/${id}`).then(d => fillSelect(sKel, d, "Pilih Kelurahan"));
            });

        });
    </script>

@endpush