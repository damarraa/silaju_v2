@extends('layouts.app')

@section('styles')
    <style>
        /* Paksa SweetAlert tampil di atas Modal Alpine */
        .swal2-container {
            z-index: 999999 !important;
        }
    </style>
@endsection

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Verifikasi Data PJU
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium hover:text-brand-500" href="{{ route('dashboard.index') }}">Dashboard /</a></li>
                <li class="font-medium text-brand-500">Verifikasi</li>
            </ol>
        </nav>
    </div>

    {{-- Main Container dengan Alpine Data --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900" x-data="{ 
                showModal: false,
                activeData: null,
                openModal(data) {
                    this.activeData = data;
                    this.showModal = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeModal() {
                    this.showModal = false;
                    this.activeData = null;
                    document.body.classList.remove('overflow-hidden');
                }
             }">

        {{-- FILTER BAR --}}
        <div class="border-b border-gray-200 p-5 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <form method="GET" action="{{ route('pju.verification') }}">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">

                    {{-- Search Input --}}
                    <div class="relative w-full md:w-1/2">
                        <button class="absolute top-1/2 left-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari ID Pelanggan / Alamat..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition" />
                    </div>

                    {{-- Filter Status --}}
                    <div class="w-full md:w-auto">
                        <select name="verification_status" onchange="this.form.submit()"
                            class="h-11 w-full md:w-64 rounded-lg border border-gray-300 bg-white px-3 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white cursor-pointer hover:border-gray-400">
                            <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>⏳ Menunggu Review
                            </option>
                            <option value="verified" {{ $statusFilter == 'verified' ? 'selected' : '' }}>✅ Sudah Diverifikasi
                            </option>
                            <option value="rejected" {{ $statusFilter == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>

                </div>
            </form>
        </div>

        {{-- TABLE LIST --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto">
                <thead class="border-b border-gray-200 bg-gray-50 text-left dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        {{-- KOLOM NOMOR --}}
                        <th
                            class="px-4 py-3 w-10 font-medium text-gray-500 text-xs uppercase dark:text-gray-400 text-center">
                            No</th>

                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Foto</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">ID / Lokasi
                        </th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Teknis</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 font-medium text-gray-500 text-xs uppercase text-right dark:text-gray-400">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($pjus as $pju)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- NOMOR URUT (Pagination Aware) --}}
                            <td class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ ($pjus->currentPage() - 1) * $pjus->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="h-12 w-12 rounded-md overflow-hidden border border-gray-200 dark:border-gray-700">
                                    @if($pju->evidence)
                                        <img src="{{ asset('storage/' . $pju->evidence) }}" class="h-full w-full object-cover"
                                            alt="Bukti">
                                    @else
                                        <div
                                            class="h-full w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                            N/A</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-semibold text-gray-800 dark:text-white">{{ $pju->id_pelanggan ?? 'Non-ID' }}</span>
                                    <span
                                        class="text-xs text-gray-500 truncate max-w-[200px]">{{ Str::limit($pju->alamat, 40) }}</span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $pju->area->nama ?? '-' }} | {{ $pju->rayon->nama ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    <div>{{ $pju->jenis_lampu }} - {{ $pju->watt }}W</div>
                                    <div>{{ $pju->merk_lampu }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($pju->verification_status == 'pending')
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 border border-yellow-200">Menunggu</span>
                                @elseif($pju->verification_status == 'verified')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 border border-green-200">Verified</span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 border border-red-200">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button @click="openModal({{ Js::from($pju->load(['trafo', 'area', 'rayon'])) }})"
                                    class="inline-flex items-center justify-center gap-1 rounded-md bg-brand-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-700 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">{{ $pjus->links() }}</div>

        {{-- ===================== MODAL DETAIL ===================== --}}
        <div x-show="showModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">

            <div @click.outside="closeModal()" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="relative w-full max-w-5xl bg-white rounded-xl shadow-2xl dark:bg-gray-900 overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Verifikasi</h3>
                        <p class="text-sm text-gray-500">ID Pelanggan: <span
                                x-text="activeData?.id_pelanggan || '-'"></span></p>
                    </div>
                    <button @click="closeModal()"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- Kiri: Visual (Foto & Peta) --}}
                        <div class="space-y-4">
                            <div class="rounded-lg border border-gray-200 overflow-hidden bg-gray-100 dark:border-gray-700">
                                <template x-if="activeData?.evidence">
                                    <img :src="'{{ asset('storage') }}/' + activeData.evidence"
                                        class="w-full h-64 object-contain bg-black/5" alt="Evidence">
                                </template>
                                <template x-if="!activeData?.evidence">
                                    <div class="h-64 flex items-center justify-center text-gray-400">Tidak ada foto</div>
                                </template>
                            </div>

                            {{-- Map Info (FIXED URL 404) --}}
                            <div
                                class="p-4 rounded-lg bg-blue-50 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800">
                                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-2">Koordinat Lokasi</h4>

                                {{-- Jika Ada Koordinat --}}
                                <template x-if="activeData?.latitude && activeData?.longitude">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="font-mono text-gray-700 dark:text-gray-300"
                                            x-text="activeData.latitude + ', ' + activeData.longitude"></span>
                                        {{-- Fix: Gunakan URL standard maps.google.com/?q= --}}
                                        <a :href="'https://www.google.com/maps?q=' + activeData.latitude + ',' + activeData.longitude"
                                            target="_blank"
                                            class="text-brand-600 hover:underline text-xs flex items-center gap-1">
                                            Buka di Maps <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </template>

                                {{-- Jika Null --}}
                                <template x-if="!activeData?.latitude || !activeData?.longitude">
                                    <span class="text-sm text-gray-500 italic">Koordinat tidak tersedia (Null)</span>
                                </template>
                            </div>
                        </div>

                        {{-- Kanan: Detail --}}
                        <div class="space-y-6">
                            {{-- ... (Bagian Detail Kanan sama seperti sebelumnya) ... --}}
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs uppercase">Wilayah Area</p>
                                    <p class="font-medium text-gray-900 dark:text-white"
                                        x-text="activeData?.area?.nama || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase">Unit Rayon</p>
                                    <p class="font-medium text-gray-900 dark:text-white"
                                        x-text="activeData?.rayon?.nama || '-'"></p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-gray-500 text-xs uppercase">Alamat Lengkap</p>
                                    <p class="font-medium text-gray-900 dark:text-white" x-text="activeData?.alamat"></p>
                                </div>
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800">

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs uppercase">Jenis / Watt</p>
                                    <p class="font-medium text-gray-900 dark:text-white"><span
                                            x-text="activeData?.jenis_lampu"></span> - <span
                                            x-text="activeData?.watt"></span>W</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase">Merk</p>
                                    <p class="font-medium text-gray-900 dark:text-white" x-text="activeData?.merk_lampu">
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase">Status</p>
                                    <p class="font-medium text-gray-900 dark:text-white capitalize"
                                        x-text="activeData?.status ? activeData.status.replace('_', ' ') : '-'"></p>
                                </div>
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800">

                            <div>
                                <p class="text-gray-500 text-xs uppercase mb-1">Sumber Gardu (Trafo)</p>
                                <div
                                    class="bg-gray-50 p-3 rounded border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <p class="font-bold text-gray-900 dark:text-white"
                                        x-text="activeData?.trafo?.id_gardu || 'Tidak Ada Data'"></p>
                                    <p class="text-xs text-gray-500" x-text="activeData?.trafo?.alamat || ''"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Buttons --}}
                <div
                    class="bg-gray-50 px-6 py-4 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex justify-end gap-3">
                    <button type="button" @click="verifyAction('reject', activeData?.id)"
                        class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 transition">
                        Tolak (Reject)
                    </button>
                    <button type="button" @click="verifyAction('approve', activeData?.id)"
                        class="rounded-lg bg-green-600 px-6 py-2 text-sm font-medium text-white hover:bg-green-700 transition shadow-md">
                        Verifikasi (Approve)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 sudah ada di app.blade.php, jadi tidak perlu CDN di sini --}}
    <script>
        function verifyAction(action, pjuId) {
            if (!pjuId) {
                Swal.fire('Error', 'ID Data tidak valid.', 'error');
                return;
            }

            const isApprove = action === 'approve';

            Swal.fire({
                title: isApprove ? 'Verifikasi Data?' : 'Tolak Data?',
                text: isApprove ? "Data akan ditandai sebagai valid." : "Data akan ditandai perlu perbaikan.",
                icon: isApprove ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonColor: isApprove ? '#10B981' : '#EF4444',
                confirmButtonText: isApprove ? 'Ya, Verifikasi!' : 'Ya, Tolak!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });

                    fetch(`{{ url('pju') }}/${pjuId}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: action })
                    })
                        .then(res => {
                            if (res.ok) {
                                Swal.fire('Berhasil', 'Status berhasil diperbarui', 'success').then(() => window.location.reload());
                            } else {
                                Swal.fire('Error', 'Gagal memproses data', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                        });
                }
            });
        }
    </script>
@endpush