<?php

namespace App\Http\Controllers\PJU;

use App\Exports\OfficerDetailExport;
use App\Exports\PJUExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PJU\StorePJURequest;
use App\Http\Requests\PJU\UpdatePJURequest;
use App\Models\Area;
use App\Models\PJU;
use App\Models\Rayon;
use App\Models\Trafo;
use App\Models\User;
use App\Notifications\DataStatusUpdated;
use App\Notifications\NewDataSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PJUController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $pjus = $query->latest()->paginate(15)->withQueryString();

        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();
        $areas = Area::where('wilayah_id', 1)->get();
        return view('pages.pju.index', compact('pjus', 'trafos', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        return view('pages.pju.create', compact('trafos', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePJURequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['verification_status'] = 'pending';

        // Formatting
        $data['jenis_lampu'] = strtoupper(trim($request->jenis_lampu));
        $data['merk_lampu'] = strtoupper(trim($request->merk_lampu));
        $data['alamat'] = ucwords(strtolower(trim($request->alamat)));
        $data['kelurahan'] = strtoupper($request->kelurahan);
        $data['kecamatan'] = strtoupper($request->kecamatan);
        $data['kabupaten'] = strtoupper($request->kabupaten);
        $data['provinsi'] = strtoupper($request->provinsi);

        if ($request->hasFile('evidence')) {
            $identifier = $data['id_pelanggan'] ?? 'NON-METER-' . Str::random(5);
            $data['evidence'] = $this->processAndUploadImage(
                $request->file('evidence'),
                $identifier
            );
        }

        $pju = PJU::create($data);
        $verifikators = User::role('verifikator')->get();

        if ($verifikators->count() > 0) {
            Notification::send($verifikators, new NewDataSubmission($pju, auth()->user()->name));
        }
        return redirect()->route('pju.create')->with('success', 'Data LPJU berhasil disimpan! Silakan input data berikutnya.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PJU $pju)
    {
        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        $rayons = Rayon::where('area_id', $pju->area_id)->orderBy('nama')->get();
        return view('pages.pju.edit', compact('pju', 'trafos', 'areas', 'rayons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePJURequest $request, PJU $pju)
    {
        $data = $request->validated();

        // Reset Status Verifikasi
        $data['verification_status'] = 'pending';
        $data['verified_at'] = null;
        $data['verified_by'] = null;

        // Formatting
        $data['jenis_lampu'] = strtoupper(trim($request->jenis_lampu));
        $data['merk_lampu'] = strtoupper(trim($request->merk_lampu));
        $data['alamat'] = ucwords(strtolower(trim($request->alamat)));
        $data['kelurahan'] = strtoupper($request->kelurahan);
        $data['kecamatan'] = strtoupper($request->kecamatan);
        $data['kabupaten'] = strtoupper($request->kabupaten);
        $data['provinsi'] = strtoupper($request->provinsi);

        if ($request->hasFile('evidence')) {
            if ($pju->evidence) {
                $this->deleteFile($pju->evidence);
            }

            $identifier = $data['id_pelanggan'] ?? 'NON-METER-' . Str::random(5);
            $data['evidence'] = $this->processAndUploadImage(
                $request->file('evidence'),
                $identifier
            );
        }

        $pju->update($data);
        return redirect()->route('pju.index')
            ->with('success', 'Data PJU berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PJU $pju)
    {
        if ($pju->evidence) {
            $this->deleteFile($pju->evidence);
        }

        $pju->delete();
        return redirect()->route('pju.index')
            ->with('success', 'Data PJU berhasil dihapus.');
    }

    /**
     * Galeri Foto PJU.
     */
    public function gallery(Request $request)
    {
        $query = PJU::with(['trafo', 'area', 'rayon']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_pelanggan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        if ($request->filled('trafo_id'))
            $query->where('trafo_id', $request->trafo_id);
        if ($request->filled('area_id'))
            $query->where('area_id', $request->area_id);
        if ($request->filled('rayon_id'))
            $query->where('rayon_id', $request->rayon_id);
        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('kondisi'))
            $query->where('kondisi_lampu', $request->kondisi);
        if ($request->filled('verification_status'))
            $query->where('verification_status', $request->verification_status);

        $pjus = $query->latest()->paginate(24)->withQueryString();

        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        return view('pages.pju.gallery', compact('pjus', 'trafos', 'areas'));
    }

    /**
     * Verifikasi di Halaman Detail.
     */
    public function verify(Request $request, PJU $pju)
    {
        $request->validate([
            'status' => 'required|in:approve,reject'
        ]);

        $status = $request->input('status') === 'approve' ? 'verified' : 'rejected';

        $pju->update([
            'verification_status' => $status,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        if ($pju->user) {
            $pju->user->notify(new DataStatusUpdated($pju, $status, auth()->user()->name));
        }

        $message = $status === 'verified' ? 'Data berhasil Diverifikasi.' : 'Data berhasil Ditolak.';
        return back()->with('success', $message);
    }

    /**
     * Khusus Verifikator.
     */
    public function verificationIndex(Request $request)
    {
        $statusFilter = $request->get('verification_status', 'pending');
        $query = PJU::with(['trafo', 'area', 'rayon']);

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        } else {
            $query->where('verification_status', 'pending');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_pelanggan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        if ($request->filled('area_id'))
            $query->where('area_id', $request->area_id);
        if ($request->filled('rayon_id'))
            $query->where('rayon_id', $request->rayon_id);

        $pjus = $query->latest()->paginate(15)->withQueryString();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        return view('pages.pju.verification', compact('pjus', 'areas', 'statusFilter'));
    }

    /**
     * Report Data PJU dan ID Pelanggan.
     */
    public function meterisasiIndex(Request $request)
    {
        $query = $this->getFilteredQuery($request);

        $pjus = $query->orderByRaw('id_pelanggan IS NULL')
            ->orderBy('id_pelanggan', 'asc')
            ->paginate(25)
            ->withQueryString();

        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();
        return view('pages.pju.meterisasi', compact('pjus', 'areas', 'trafos'));
    }

    /**
     * Report Data PJU dan Foto.
     */
    public function visualIndex(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $pjus = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        $trafos = Trafo::select('id', 'id_gardu', 'alamat')->get();

        return view('pages.pju.visual', compact('pjus', 'areas', 'trafos'));
    }

    /**
     * Report Realisasi.
     */
    public function realisasiIndex(Request $request)
    {
        $query = PJU::select('trafo_id')
            ->with('trafo')
            // 1. Group METERISASI
            ->selectRaw("COUNT(CASE WHEN status = 'meterisasi' THEN 1 END) as met_count")
            ->selectRaw("SUM(CASE WHEN status = 'meterisasi' THEN watt ELSE 0 END) as met_watt")
            ->selectRaw("SUM(CASE WHEN status = 'meterisasi' THEN daya ELSE 0 END) as met_va")
            // 2. Group NON-METERISASI
            ->selectRaw("COUNT(CASE WHEN status = 'non_meterisasi' THEN 1 END) as non_count")
            ->selectRaw("SUM(CASE WHEN status = 'non_meterisasi' THEN watt ELSE 0 END) as non_watt")
            ->selectRaw("SUM(CASE WHEN status = 'non_meterisasi' THEN daya ELSE 0 END) as non_va")
            // 3. Group KONDISI RUSAK (Status apapun, yang penting rusak)
            ->selectRaw("COUNT(CASE WHEN kondisi_lampu = 'rusak' THEN 1 END) as rusak_count")
            ->selectRaw("SUM(CASE WHEN kondisi_lampu = 'rusak' THEN watt ELSE 0 END) as rusak_watt")
            ->selectRaw("SUM(CASE WHEN kondisi_lampu = 'rusak' THEN daya ELSE 0 END) as rusak_va")
            // 4. TOTAL KESELURUHAN (Titik Lampu)
            ->selectRaw("COUNT(id) as total_titik")
            ->groupBy('trafo_id');

        $query->whereHas('trafo', function ($q) use ($request) {

            // Filter Search ID Gardu
            if ($request->filled('search')) {
                $q->where('id_gardu', 'like', "%{$request->search}%");
            }
            // Filter Rayon
            if ($request->filled('rayon_id')) {
                $q->where('rayon_id', $request->rayon_id);
            }
            // Filter Kabupaten
            if ($request->filled('kabupaten')) {
                $q->where('kabupaten', $request->kabupaten);
            }
            // Filter Kecamatan
            if ($request->filled('kecamatan')) {
                $q->where('kecamatan', $request->kecamatan);
            }
            // Filter Kelurahan
            if ($request->filled('kelurahan')) {
                $q->where('kelurahan', $request->kelurahan);
            }
        });

        $realisasi = $query->paginate(20)->withQueryString();
        $rayons = Rayon::orderBy('nama')->get();
        $kabupatens = Trafo::select('kabupaten')->distinct()->whereNotNull('kabupaten')->orderBy('kabupaten')->pluck('kabupaten');

        $kecamatans = [];
        if ($request->filled('kabupaten')) {
            $kecamatans = Trafo::select('kecamatan')
                ->where('kabupaten', $request->kabupaten)
                ->distinct()->orderBy('kecamatan')->pluck('kecamatan');
        }

        $kelurahans = [];
        if ($request->filled('kecamatan')) {
            $kelurahans = Trafo::select('kelurahan')
                ->where('kecamatan', $request->kecamatan)
                ->distinct()->orderBy('kelurahan')->pluck('kelurahan');
        }

        return view('pages.pju.realisasi', compact('realisasi', 'rayons', 'kabupatens', 'kecamatans', 'kelurahans'));
    }

    /**
     * Rekap Jenis Lampu PJU.
     */
    public function rekapJenisIndex(Request $request)
    {
        $filterPju = function ($query) use ($request) {
            if ($request->filled('search')) {
                $query->where('jenis_lampu', 'like', "%{$request->search}%");
            }
        };

        $query = Trafo::query()
            ->with(['pjus' => $filterPju])
            ->whereHas('pjus', $filterPju);

        if ($request->filled('search')) {
            $query->where('id_gardu', 'like', "%{$request->search}%")
                ->orWhere('alamat', 'like', "%{$request->search}%");
        }
        if ($request->filled('rayon_id'))
            $query->where('rayon_id', $request->rayon_id);
        if ($request->filled('kabupaten'))
            $query->where('kabupaten', $request->kabupaten);
        if ($request->filled('kecamatan'))
            $query->where('kecamatan', $request->kecamatan);
        if ($request->filled('kelurahan'))
            $query->where('kelurahan', $request->kelurahan);

        $trafos = $query->orderBy('id_gardu')->paginate(10)->withQueryString();
        $rayons = Rayon::orderBy('nama')->get();
        $kabupatens = Trafo::select('kabupaten')->whereNotNull('kabupaten')->distinct()->orderBy('kabupaten')->pluck('kabupaten');

        $kecamatans = [];
        if ($request->filled('kabupaten')) {
            $kecamatans = Trafo::select('kecamatan')->where('kabupaten', $request->kabupaten)->distinct()->orderBy('kecamatan')->pluck('kecamatan');
        }

        $kelurahans = [];
        if ($request->filled('kecamatan')) {
            $kelurahans = Trafo::select('kelurahan')->where('kecamatan', $request->kecamatan)->distinct()->orderBy('kelurahan')->pluck('kelurahan');
        }

        return view('pages.pju.rekap_jenis', compact('trafos', 'rayons', 'kabupatens', 'kecamatans', 'kelurahans'));
    }

    /**
     * Rekap Harian.
     */
    public function dailyRecapIndex(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $query = PJU::with(['user', 'trafo', 'rayon', 'area'])
            ->latest();

        $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('nyala_siang')) {
            $query->where('nyala_siang', true);
        }

        $pjus = $query->paginate(20)->withQueryString();
        $rayons = Rayon::orderBy('nama')->get();

        $officersQuery = User::query();
        if ($request->filled('rayon_id')) {
            $officersQuery->where('rayon_id', $request->rayon_id);
        }
        $officers = $officersQuery->orderBy('name')->get();

        return view('pages.pju.rekap_harian', compact('pjus', 'rayons', 'officers', 'startDate', 'endDate'));
    }

    /**
     * Rekap Total.
     */
    public function rekapTotalIndex(Request $request)
    {
        $groupBy = $request->input('group_by', 'gardu');
        $query = PJU::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('nyala_siang')) {
            $query->where('nyala_siang', true);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kabupaten')) {
            $query->whereHas('trafo', function ($q) use ($request) {
                $q->where('kabupaten', $request->kabupaten);
            });
        }

        if ($groupBy == 'rayon') {
            $query->select('rayon_id')
                ->with('rayon')
                ->groupBy('rayon_id');
        } else {
            $query->select('trafo_id')
                ->with('trafo')
                ->groupBy('trafo_id');

            if ($request->filled('rayon_id')) {
                $query->where('rayon_id', $request->rayon_id);
            }
        }

        $query->selectRaw('COUNT(id) as total_titik')
            ->selectRaw('SUM(watt) as total_watt')
            ->selectRaw("COUNT(CASE WHEN status = 'meterisasi' THEN 1 END) as count_meter")
            ->selectRaw("COUNT(CASE WHEN status = 'non_meterisasi' THEN 1 END) as count_non")
            ->selectRaw("COUNT(CASE WHEN status = 'ilegal' THEN 1 END) as count_ilegal")
            ->selectRaw("COUNT(CASE WHEN nyala_siang = 1 THEN 1 END) as count_nyala_siang");

        $data = $query->paginate(20)->withQueryString();

        $rayons = Rayon::orderBy('nama')->get();
        $kabupatens = Trafo::select('kabupaten')->whereNotNull('kabupaten')->distinct()->orderBy('kabupaten')->pluck('kabupaten');

        return view('pages.pju.rekap_total', compact('data', 'rayons', 'kabupatens', 'groupBy'));
    }

    /**
     * Report Data Petugas.
     */
    public function officerPerformance(Request $request)
    {
        $query = User::query();
        $query->with('rayon');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('rayon', function ($r) use ($search) {
                        $r->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }

        $officers = $query->withCount([
            'pjus' => function ($q) {
                // Opsional: Jika ingin menghitung hanya PJU yang valid/verified
                // $q->where('verification_status', 'verified');
            }
        ])
            ->whereHas('pjus')
            ->orderByDesc('pjus_count')
            ->paginate(20)
            ->withQueryString();

        $rayons = Rayon::orderBy('nama')->get();
        return view('pages.pju.officer_performance', compact('officers', 'rayons'));
    }

    /**
     * Detail Report Data Petugas.
     */
    public function officerDetail(Request $request, User $user)
    {
        $query = $this->getDetailQuery($request, $user->id);

        $details = $query->orderByDesc('total_input')
            ->paginate(20)
            ->withQueryString();

        return view('pages.pju.officer_detail', compact('user', 'details'));
    }

    /**
     * Export Excel - Rincian Data Petugas.
     */
    public function officerDetailExportExcel(Request $request, User $user)
    {
        $query = $this->getDetailQuery($request, $user->id);
        $filename = 'Laporan_Kinerja_' . str_replace(' ', '_', $user->name) . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new OfficerDetailExport($query, $user), $filename);
    }

    /**
     * Export PDF - Rincian Data Petugas.
     */
    public function officerDetailExportPdf(Request $request, User $user)
    {
        $details = $this->getDetailQuery($request, $user->id)
            ->orderByDesc('total_input')
            ->get();

        $pdf = Pdf::loadView('exports.officer_detail_pdf', compact('user', 'details'));
        $filename = 'Laporan_Kinerja_' . str_replace(' ', '_', $user->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel.
     */
    public function exportExcel(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        return Excel::download(new PJUExport($query), 'laporan-pju-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export PDF.
     */
    public function exportPdf(Request $request)
    {
        $pjus = $this->getFilteredQuery($request)->get();

        $pdf = Pdf::loadView('exports.pju_pdf', compact('pjus'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pju-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Menghapus file dari storage.
     */
    private function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Proses dan Upload gambar.
     */
    private function processAndUploadImage($file, $identifier)
    {
        $safeIdentifier = str_replace(['/', '\\', ' '], '-', $identifier);
        $fileName = "{$safeIdentifier}-" . Str::random(10) . '.jpg';
        $directory = 'pju-evidence';

        $destinationFolder = public_path("storage/{$directory}");
        $this->ensureDirectoryExists($destinationFolder);
        $destinationPath = "{$destinationFolder}/{$fileName}";

        if ($this->resizeAndSaveImage($file, $destinationPath)) {
            return "{$directory}/{$fileName}";
        }

        return null;
    }

    /**
     * Resize gambar menggunakan GD Library.
     */
    private function resizeAndSaveImage($file, $destinationPath)
    {
        $imageType = strtolower($file->getClientOriginalExtension());
        $image = match ($imageType) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png' => imagecreatefrompng($file->getRealPath()),
            'webp' => imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (!$image)
            return false;

        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = 1080;

        if ($width <= $newWidth) {
            $newWidth = $width;
            $newHeight = $height;
        } else {
            $newHeight = round(($newWidth / $width) * $height);
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        if (in_array($imageType, ['png', 'webp'])) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
        }

        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $result = imagejpeg($resizedImage, $destinationPath, 75);

        imagedestroy($image);
        imagedestroy($resizedImage);

        return $result;
    }

    /**
     * Membuat folder.
     */
    private function ensureDirectoryExists($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
    }

    /**
     * Private Helper: Query filter logic untuk Rincian Gardu.
     */
    private function getDetailQuery($request, $userId)
    {
        $query = PJU::select('trafo_id', DB::raw('count(*) as total_input'))
            ->with('trafo')
            ->where('user_id', $userId)
            ->groupBy('trafo_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('trafo', function ($q) use ($search) {
                $q->where('id_gardu', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Private Helper: Query filter logic.
     */
    private function getFilteredQuery(Request $request)
    {
        $query = PJU::with(['trafo', 'area', 'rayon']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_pelanggan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('merk_lampu', 'like', "%{$search}%");
            });
        }

        if ($request->filled('trafo_id')) {
            $query->where('trafo_id', $request->trafo_id);
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi_lampu', $request->kondisi);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        return $query;
    }
}
