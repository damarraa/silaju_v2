@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Edit Data PJU
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li><a class="font-medium hover:text-brand-500" href="{{ route('pju.index') }}">Data PJU /</a></li>
                <li class="font-medium text-brand-500">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">

        <form action="{{ route('pju.update', $pju->id) }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-6.5"
            {{-- UPDATE DI SINI: Tambahkan lat & lng ke x-data --}}
            x-data="{ 
                statusPJU: '{{ old('status', $pju->status) }}',
                lat: {{ old('latitude', $pju->latitude ?? -0.5071) }},
                lng: {{ old('longitude', $pju->longitude ?? 101.4478) }},
                previewImage: '{{ $pju->evidence ? asset('storage/' . $pju->evidence) : '' }}',
                loadingVerify: false,

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewImage = URL.createObjectURL(file);
                    }
                },

                // --- SWEETALERT2 LOGIC ---
                verifyData(status) {
                    const isApprove = status === 'approve';
                    const actionText = isApprove ? 'Memverifikasi' : 'Menolak';
                    const btnColor = isApprove ? '#10B981' : '#EF4444'; 

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: `Anda akan ${actionText} data PJU ini.`,
                        icon: isApprove ? 'question' : 'warning',
                        showCancelButton: true,
                        confirmButtonColor: btnColor,
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: `Ya, ${actionText}!`,
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.loadingVerify = true;

                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch('{{ url('pju/' . $pju->id . '/verify') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ status: status })
                            })
                            .then(response => {
                                if (response.ok) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Status berhasil diperbarui menjadi ${isApprove ? 'Verified' : 'Rejected'}.`,
                                        icon: 'success',
                                        confirmButtonColor: '#3B82F6'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    throw new Error('Gagal memproses request.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat memproses data.',
                                    icon: 'error'
                                });
                                this.loadingVerify = false;
                            });
                        }
                    });
                }
            }">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="flex flex-col gap-6">

                    {{-- 1. FOTO BUKTI --}}
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            1. Foto Bukti Lapangan
                        </label>
                        <div class="relative w-full">
                            <input type="file" name="evidence" id="evidence" accept="image/*" capture="environment"
                                @change="handleFileChange"
                                class="absolute inset-0 z-50 h-full w-full opacity-0 cursor-pointer" />

                            <div class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition dark:bg-gray-900 dark:border-gray-600 dark:hover:border-gray-500"
                                :class="previewImage ? 'border-brand-500' : 'border-gray-300'">

                                <div x-show="!previewImage" class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <div class="mb-3 p-2 rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/20">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500">Tap untuk ganti foto</p>
                                </div>

                                <img x-show="previewImage" :src="previewImage" class="absolute inset-0 w-full h-full object-cover rounded-lg" />
                                <div x-show="previewImage" class="absolute bottom-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded-md backdrop-blur-sm">
                                    Klik untuk ubah
                                </div>
                            </div>
                        </div>
                        @error('evidence') <span class="text-xs text-error-500 mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. KOORDINAT --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            2. Titik Koordinat
                        </label>
                        
                        <div class="mb-4">
                            {{-- Component Map Picker --}}
                            {{-- Kita passing prop :lat & :lng untuk inisialisasi Map (PHP) --}}
                            {{-- Input di dalam component akan binding ke x-data lat & lng (Alpine) --}}
                            <x-form.map-picker 
                                :lat="old('latitude', $pju->latitude ?? -0.5071)" 
                                :lng="old('longitude', $pju->longitude ?? 101.4478)" 
                            />
                        </div>

                        {{-- Area & Rayon --}}
                        <div class="grid grid-cols-2 gap-4 rounded-lg bg-gray-50 p-4 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500 uppercase">Unit Area</label>
                                <select name="area_id" id="area_id" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $pju->area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500 uppercase">Unit Rayon</label>
                                <select name="rayon_id" id="rayon_id" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Rayon</option>
                                    @foreach($rayons as $rayon)
                                        <option value="{{ $rayon->id }}" {{ old('rayon_id', $pju->rayon_id) == $rayon->id ? 'selected' : '' }}>
                                            {{ $rayon->kode_rayon }} - {{ $rayon->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rayon_id') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Wilayah Administrasi --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">PROVINSI</label>
                                <select id="provinsi" name="provinsi" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="">Memuat...</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KAB/KOTA</label>
                                <select id="kabupaten" name="kabupaten" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="{{ $pju->kabupaten }}">{{ $pju->kabupaten }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KECAMATAN</label>
                                <select id="kecamatan" name="kecamatan" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="{{ $pju->kecamatan }}">{{ $pju->kecamatan }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-500">KELURAHAN</label>
                                <select id="kelurahan" name="kelurahan" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="{{ $pju->kelurahan }}">{{ $pju->kelurahan }}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">DETAIL ALAMAT</label>
                            <textarea name="alamat" rows="2" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">{{ old('alamat', $pju->alamat) }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-6">

                    {{-- 3. IDENTITAS PJU --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            3. Identitas PJU
                        </label>

                        <div class="space-y-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Sumber Gardu (Trafo)</label>
                                <div class="relative z-20 bg-transparent">
                                    <select name="trafo_id" class="dark:bg-dark-900 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                        <option value="">Pilih Gardu / Trafo</option>
                                        @foreach($trafos as $trafo)
                                            <option value="{{ $trafo->id }}" {{ old('trafo_id', $pju->trafo_id) == $trafo->id ? 'selected' : '' }}>
                                                {{ $trafo->id_gardu }} - {{ Str::limit($trafo->alamat, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500"><svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Meter</label>
                                <select name="status" x-model="statusPJU" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="meterisasi">Meterisasi (Ada KWH)</option>
                                    <option value="non_meterisasi">Non Meterisasi (Abodemen)</option>
                                    <option value="ilegal">Ilegal / Liar</option>
                                </select>
                            </div>

                            <div x-show="statusPJU === 'meterisasi'" x-transition class="grid grid-cols-2 gap-4 border-t pt-4 border-dashed border-gray-200 dark:border-gray-700">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500">ID Pelanggan</label>
                                    <input type="number" name="id_pelanggan" value="{{ old('id_pelanggan', $pju->id_pelanggan) }}" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500">Daya (VA)</label>
                                    <select name="daya" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                        <option value="">-</option>
                                        @foreach(['450', '900', '1300', '2200', '3500', '5500', '>5500'] as $d)
                                            <option value="{{ $d }}" {{ old('daya', $pju->daya) == $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. SPESIFIKASI TEKNIS --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            4. Spesifikasi Teknis
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Lampu</label>
                                <input list="jenis-list" name="jenis_lampu" value="{{ old('jenis_lampu', $pju->jenis_lampu) }}" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                <datalist id="jenis-list"><option value="LED"><option value="SON-T"><option value="MERCURY"><option value="LHE"><option value="SOLAR CELL"></datalist>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Merk</label>
                                <input list="merk-list" name="merk_lampu" value="{{ old('merk_lampu', $pju->merk_lampu) }}" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                <datalist id="merk-list"><option value="PHILIPS"><option value="OSRAM"><option value="PANASONIC"><option value="HANNOCS"><option value="CHINA BRAND"></datalist>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Titik</label>
                                <input type="number" name="jumlah_lampu" value="{{ old('jumlah_lampu', $pju->jumlah_lampu) }}" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Watt (Total)</label>
                                <input type="number" name="watt" value="{{ old('watt', $pju->watt) }}" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white" />
                            </div>
                        </div>
                    </div>

                    {{-- 5. OPERASIONAL --}}
                    <div>
                        <label class="mb-3 block text-sm font-bold text-gray-800 dark:text-white">
                            5. Operasional
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Kondisi</label>
                                <select name="kondisi_lampu" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="baik" {{ old('kondisi_lampu', $pju->kondisi_lampu) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak" {{ old('kondisi_lampu', $pju->kondisi_lampu) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Tindak Lanjut</label>
                                <select name="tindak_lanjut" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="dibiarkan" {{ old('tindak_lanjut', $pju->tindak_lanjut) == 'dibiarkan' ? 'selected' : '' }}>Dibiarkan</option>
                                    <option value="bongkar" {{ old('tindak_lanjut', $pju->tindak_lanjut) == 'bongkar' ? 'selected' : '' }}>Bongkar</option>
                                    <option value="putus" {{ old('tindak_lanjut', $pju->tindak_lanjut) == 'putus' ? 'selected' : '' }}>Putus Jaringan</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Panel Kontrol</label>
                                <select name="sistem_operasi" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="photo_cell" {{ old('sistem_operasi', $pju->sistem_operasi) == 'photo_cell' ? 'selected' : '' }}>Photo Cell</option>
                                    <option value="timer" {{ old('sistem_operasi', $pju->sistem_operasi) == 'timer' ? 'selected' : '' }}>Timer</option>
                                    <option value="manual" {{ old('sistem_operasi', $pju->sistem_operasi) == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Installasi</label>
                                <select name="installasi" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="kabel_udara" {{ old('installasi', $pju->installasi) == 'kabel_udara' ? 'selected' : '' }}>Kabel Udara</option>
                                    <option value="kabel_tanah" {{ old('installasi', $pju->installasi) == 'kabel_tanah' ? 'selected' : '' }}>Kabel Tanah</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Kepemilikan</label>
                                <select name="kepemilikan" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="pemda" {{ old('kepemilikan', $pju->kepemilikan) == 'pemda' ? 'selected' : '' }}>PEMDA</option>
                                    <option value="swadaya" {{ old('kepemilikan', $pju->kepemilikan) == 'swadaya' ? 'selected' : '' }}>Swadaya</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Peruntukan</label>
                                <select name="peruntukan" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="jalan" {{ old('peruntukan', $pju->peruntukan) == 'jalan' ? 'selected' : '' }}>Jalan Umum</option>
                                    <option value="taman" {{ old('peruntukan', $pju->peruntukan) == 'taman' ? 'selected' : '' }}>Taman</option>
                                    <option value="fasilitas_umum" {{ old('peruntukan', $pju->peruntukan) == 'fasilitas_umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-500">Menyala Siang Hari?</label>
                                <select name="nyala_siang" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-brand-300 dark:border-gray-700 dark:text-white">
                                    <option value="0" {{ old('nyala_siang', $pju->nyala_siang) == 0 ? 'selected' : '' }}>Tidak (Normal)</option>
                                    <option value="1" {{ old('nyala_siang', $pju->nyala_siang) == 1 ? 'selected' : '' }}>Ya (Menyala Terus)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 border-t border-gray-200 dark:bg-gray-900/95 dark:border-gray-800 -mx-4 md:static md:bg-transparent md:p-0 md:border-none md:mx-0 z-50">
                
                {{-- Left: Verifikasi Buttons (Hanya Role tertentu) --}}
                <div class="flex items-center gap-3 w-full sm:w-auto p-2 rounded-lg bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-2 px-2">
                        <span class="text-xs font-bold text-gray-500 uppercase">Status:</span>
                        @if($pju->verification_status == 'verified')
                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Verified</span>
                        @elseif($pju->verification_status == 'rejected')
                            <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Rejected</span>
                        @else
                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                        @endif
                    </div>

                    {{-- Hanya tampil jika user adalah verifikator --}}
                    @if(auth()->user()->hasRole('verifikator') || auth()->user()->hasRole('super_admin'))
                    <div class="flex gap-1">
                        <button type="button" @click="verifyData('approve')" :disabled="loadingVerify" 
                            class="rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700 disabled:opacity-50 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Approve
                        </button>
                        <button type="button" @click="verifyData('reject')" :disabled="loadingVerify"
                            class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 disabled:opacity-50 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reject
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Right: Submit Buttons --}}
                <div class="flex gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('pju.index') }}" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Batal
                    </a>
                    {{-- Hanya tampil jika user punya permission edit --}}
                    @can('pju_edit')
                        <button type="submit" class="flex items-center justify-center rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 focus:ring-4 focus:ring-brand-500/20">
                            Update Data
                        </button>
                    @endcan
                </div>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            // --- AJAX: AREA -> RAYON ---
            const sArea = document.getElementById('area_id');
            const sRayon = document.getElementById('rayon_id');

            sArea.addEventListener('change', function() {
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

            // --- API Wilayah (EMSIFA) ---
            const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";
            const sProv = document.getElementById('provinsi');
            const sKab = document.getElementById('kabupaten');
            const sKec = document.getElementById('kecamatan');
            const sKel = document.getElementById('kelurahan');

            const savedProvName = "{{ $pju->provinsi }}";
            const savedKabName = "{{ $pju->kabupaten }}";
            const savedKecName = "{{ $pju->kecamatan }}";
            const savedKelName = "{{ $pju->kelurahan }}";

            async function fetchApi(ep) { try { return (await fetch(`${baseUrl}/${ep}.json`)).json(); } catch { return []; } }
            
            function fillSelect(el, data, ph, savedVal = null) {
                el.innerHTML = `<option value="">${ph}</option>`;
                data.forEach(i => { 
                    let o = document.createElement('option'); 
                    o.value = i.name; 
                    o.dataset.id = i.id; 
                    o.text = i.name; 
                    if(savedVal && i.name === savedVal) o.selected = true;
                    el.appendChild(o); 
                });
                el.disabled = false;
            }

            function findIdByName(data, name) {
                let found = data.find(item => item.name === name);
                return found ? found.id : null;
            }

            fetchApi('provinces').then(async provinces => {
                fillSelect(sProv, provinces, "Pilih Provinsi", savedProvName);
                let provId = findIdByName(provinces, savedProvName);
                if(provId) {
                    let regencies = await fetchApi(`regencies/${provId}`);
                    fillSelect(sKab, regencies, "Pilih Kab/Kota", savedKabName);
                    let kabId = findIdByName(regencies, savedKabName);
                    if(kabId) {
                        let districts = await fetchApi(`districts/${kabId}`);
                        fillSelect(sKec, districts, "Pilih Kecamatan", savedKecName);
                        let kecId = findIdByName(districts, savedKecName);
                        if(kecId) {
                            let villages = await fetchApi(`villages/${kecId}`);
                            fillSelect(sKel, villages, "Pilih Kelurahan", savedKelName);
                        }
                    }
                }
            });

            sProv.addEventListener('change', function() {
                sKab.innerHTML='<option>Loading...</option>'; sKab.disabled=true;
                if(this.options[this.selectedIndex].dataset.id) fetchApi(`regencies/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKab, d, "Pilih Kab/Kota"));
            });
            sKab.addEventListener('change', function() {
                sKec.innerHTML='<option>Loading...</option>'; sKec.disabled=true;
                if(this.options[this.selectedIndex].dataset.id) fetchApi(`districts/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKec, d, "Pilih Kecamatan"));
            });
            sKec.addEventListener('change', function() {
                sKel.innerHTML='<option>Loading...</option>'; sKel.disabled=true;
                if(this.options[this.selectedIndex].dataset.id) fetchApi(`villages/${this.options[this.selectedIndex].dataset.id}`).then(d => fillSelect(sKel, d, "Pilih Kelurahan"));
            });
        });
    </script>
@endpush