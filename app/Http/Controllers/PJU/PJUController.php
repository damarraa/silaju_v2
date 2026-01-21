<?php

namespace App\Http\Controllers\PJU;

use App\Http\Controllers\Controller;
use App\Http\Requests\PJU\StorePJURequest;
use App\Models\PJU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PJUController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('pju.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pju.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePJURequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

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
    public function edit(string $id)
    {
        // Buat view edit nanti
        return view('pages.pju.edit', compact('pju'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePJURequest $request, PJU $pju)
    {
        $data = $request->validated();

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
}
