<?php

namespace App\Http\Controllers\PJU;

use App\Exports\PJUExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PJU\StorePJURequest;
use App\Http\Requests\PJU\UpdatePJURequest;
use App\Models\Area;
use App\Models\PJU;
use App\Models\Rayon;
use App\Models\Trafo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        PJU::create($data);
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
     * Filter logic.
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
