<?php

namespace App\Http\Controllers\Trafo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trafo\StoreTrafoRequest;
use App\Models\Area;
use App\Models\Rayon;
use App\Models\Trafo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrafoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Trafo::with(['area', 'rayon']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_gardu', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }

        $trafos = $query->latest()->paginate(15)->withQueryString();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        return view('pages.trafo.index', compact('trafos', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        return view('pages.trafo.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrafoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Formatting
        $data['id_gardu'] = strtoupper(trim($request->id_gardu));
        $data['merk'] = strtoupper(trim($request->merk));
        $data['sr'] = strtoupper(trim($request->sr));
        $data['alamat'] = ucwords(strtolower(trim($request->alamat)));
        $data['kelurahan'] = strtoupper($request->kelurahan);
        $data['kecamatan'] = strtoupper($request->kecamatan);
        $data['kabupaten'] = strtoupper($request->kabupaten);
        $data['provinsi'] = strtoupper($request->provinsi);

        if ($request->hasFile('evidence')) {
            $identifier = $data['id_gardu'] ?? 'TRAFO-' . Str::random(5);

            $data['evidence'] = $this->processAndUploadImage(
                $request->file('evidence'),
                $identifier
            );
        }

        Trafo::create($data);
        return redirect()->route('trafo.index')
            ->with('success', 'Data Trafo berhasil disimpan! Silakan input data berikutnya.');
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
    public function edit(Trafo $trafo)
    {
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();
        $rayons = Rayon::where('area_id', $trafo->area_id)->orderBy('nama')->get();
        return view('pages.trafo.edit', compact('trafo', 'areas', 'rayons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTrafoRequest $request, Trafo $trafo)
    {
        $data = $request->validated();

        // Formatting
        $data['id_gardu'] = strtoupper(trim($request->id_gardu));
        $data['merk'] = strtoupper(trim($request->merk));
        $data['sr'] = strtoupper(trim($request->sr));
        $data['alamat'] = ucwords(strtolower(trim($request->alamat)));
        $data['kelurahan'] = strtoupper($request->kelurahan);
        $data['kecamatan'] = strtoupper($request->kecamatan);
        $data['kabupaten'] = strtoupper($request->kabupaten);
        $data['provinsi'] = strtoupper($request->provinsi);

        if ($request->hasFile('evidence')) {
            if ($trafo->evidence) {
                $this->deleteFile($trafo->evidence);
            }

            $identifier = $data['id_gardu'] ?? 'TRAFO-' . Str::random(5);
            $data['evidence'] = $this->processAndUploadImage(
                $request->file('evidence'),
                $identifier
            );
        }

        $trafo->update($data);
        return redirect()->route('trafo.index')
            ->with('success', 'Data Trafo berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trafo $trafo)
    {
        if ($trafo->evidence) {
            $this->deleteFile($trafo->evidence);
        }

        $trafo->delete();
        return redirect()->route('trafo.index')
            ->with('success', 'Data Trafo berhasil dihapus.');
    }

    /**
     * Galeri Foto Trafo.
     */
    public function gallery(Request $request)
    {
        $query = Trafo::with(['area', 'rayon']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_gardu', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        if ($request->filled('area_id'))
            $query->where('area_id', $request->area_id);
        if ($request->filled('rayon_id'))
            $query->where('rayon_id', $request->rayon_id);

        $trafos = $query->latest()->paginate(24)->withQueryString();
        $areas = Area::where('wilayah_id', 1)->orderBy('nama')->get();

        return view('pages.trafo.gallery', compact('trafos', 'areas'));
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
        $directory = 'trafo-evidence';

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
}
