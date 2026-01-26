@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Input Data Trafo / Gardu
        </h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        <form action="{{ route('trafo.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-6.5" 
            x-data="{ 
                lat: '{{ old('latitude', -0.5071) }}', 
                lng: '{{ old('longitude', 101.4478) }}',
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
                            1. Foto Fisik Gardu <span class="text-error-500">*</span>
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
                            2. Lokasi & Unit Kerja
                        </label>

                        <div class="mb-4">
                            <x-form.map-picker ::lat="lat" ::lng="lng" />
                        </div>

                        <div
                            class="grid grid-cols-2 gap-4 rounded-lg bg-gray-50 p-4 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500 uppercase">Unit Area <span
                                        class="text-error-500">*</span></label>
                                <select name="area_id" id="area_id"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500 uppercase">Unit Rayon <span
                                        class="text-error-500">*</span></label>
                                <select name="rayon_id" id="rayon_id" disabled
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-600 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed">
                                    <option value="">Pilih Area Dulu</option>
                                </select>
                                @error('rayon_id') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
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
                            <textarea name="alamat" rows="2" placeholder="Nama Jalan, Patokan"
                                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-6">

                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            3. Identitas & Spesifikasi Trafo
                        </label>

                        <div class="space-y-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    No. ID Gardu / Trafo <span class="text-error-500">*</span>
                                </label>
                                <input type="text" name="id_gardu" value="{{ old('id_gardu') }}"
                                    placeholder="Contoh: GD-PKU-001"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white uppercase" />
                                @error('id_gardu') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kapasitas Daya (kVA) <span class="text-error-500">*</span>
                                </label>
                                <div class="relative z-20 bg-transparent">
                                    <select name="daya"
                                        class="dark:bg-dark-900 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                        <option value="">Pilih Kapasitas</option>
                                        <option value="25 kVA">25 kVA</option>
                                        <option value="50 kVA">50 kVA</option>
                                        <option value="100 kVA">100 kVA</option>
                                        <option value="160 kVA">160 kVA</option>
                                        <option value="200 kVA">200 kVA</option>
                                        <option value="250 kVA">250 kVA</option>
                                        <option value="315 kVA">315 kVA</option>
                                        <option value="400 kVA">400 kVA</option>
                                        <option value="630 kVA">630 kVA</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <span class="absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Merk Trafo <span class="text-error-500">*</span>
                                </label>
                                <input list="merk-trafo-list" name="merk" value="{{ old('merk') }}"
                                    placeholder="Pilih atau Ketik Merk..."
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white uppercase" />
                                <datalist id="merk-trafo-list">
                                    <option value="TRAFINDO">
                                    <option value="BAMBANG DJAJA (B&D)">
                                    <option value="STARLITE">
                                    <option value="UNINDO">
                                    <option value="SCHNEIDER">
                                    <option value="SINTRA">
                                    <option value="CENTRADO">
                                </datalist>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Keterangan Panel / SR
                                </label>
                                <input type="text" name="sr" value="{{ old('sr') }}"
                                    placeholder="Contoh: 2 Jurusan, Panel Lengkap"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div
                class="mt-8 flex justify-end gap-3 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 border-t border-gray-200 dark:bg-gray-900/95 dark:border-gray-800 -mx-4 md:static md:bg-transparent md:p-0 md:border-none md:mx-0 z-50">
                <a href="{{ route('trafo.index') }}"
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- AJAX: AREA -> RAYON (FIXED NAME -> NAMA) ---
            const sArea = document.getElementById('area_id');
            const sRayon = document.getElementById('rayon_id');

            sArea.addEventListener('change', function () {
                const areaId = this.value;
                sRayon.innerHTML = '<option value="">Memuat...</option>';
                sRayon.disabled = true;

                if (areaId) {
                    fetch(`{{ url('/ajax/rayons') }}/${areaId}`)
                        .then(res => res.json())
                        .then(data => {
                            sRayon.innerHTML = '<option value="">Pilih Rayon</option>';
                            data.forEach(rayon => {
                                const option = document.createElement('option');
                                option.value = rayon.id;
                                option.text = `${rayon.kode_rayon} - ${rayon.nama}`;
                                sRayon.appendChild(option);
                            });
                            sRayon.disabled = false;
                        })
                        .catch(err => {
                            console.error(err);
                            sRayon.innerHTML = '<option value="">Gagal memuat rayon</option>';
                        });
                } else {
                    sRayon.innerHTML = '<option value="">Pilih Area Dulu</option>';
                }
            });

            // --- API WILAYAH (EMSIFA) ---
            const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";
            const sProv = document.getElementById('provinsi');
            const sKab = document.getElementById('kabupaten');
            const sKec = document.getElementById('kecamatan');
            const sKel = document.getElementById('kelurahan');

            async function fetchApi(ep) { try { return (await fetch(`${baseUrl}/${ep}.json`)).json(); } catch { return []; } }
            function fillSelect(el, data, ph) {
                el.innerHTML = `<option value="">${ph}</option>`;
                data.forEach(i => { let o = document.createElement('option'); o.value = i.name; o.dataset.id = i.id; o.text = i.name; el.appendChild(o); });
                el.disabled = false;
            }

            fetchApi('provinces').then(d => fillSelect(sProv, d, "Pilih Provinsi"));

            sProv.addEventListener('change', function () {
                sKab.innerHTML = '<option>Loading...</option>'; sKab.disabled = true; sKec.disabled = true; sKel.disabled = true;
                if (this.options[this.selectedIndex].dataset.id) fetchApi(`regencies/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKab, d, "Pilih Kab/Kota"));
            });
            sKab.addEventListener('change', function () {
                sKec.innerHTML = '<option>Loading...</option>'; sKec.disabled = true; sKel.disabled = true;
                if (this.options[this.selectedIndex].dataset.id) fetchApi(`districts/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKec, d, "Pilih Kecamatan"));
            });
            sKec.addEventListener('change', function () {
                sKel.innerHTML = '<option>Loading...</option>'; sKel.disabled = true;
                if (this.options[this.selectedIndex].dataset.id) fetchApi(`villages/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKel, d, "Pilih Kelurahan"));
            });
        });
    </script>
@endpush