<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PJU;
use App\Models\Rayon;
use App\Models\Trafo;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    /**
     * Maps Keseluruhan.
     */
    public function index(Request $request)
    {
        $query = PJU::with(['trafo:id,id_gardu,latitude,longitude', 'rayon:id,nama']);

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kondisi_lampu')) {
            $query->where('kondisi_lampu', $request->kondisi_lampu);
        }

        $pjus = $query->whereHas('trafo', function ($q) {
            $q->whereNotNull('latitude')->whereNotNull('longitude');
        })->get();

        $groupedPjus = $pjus->groupBy('trafo_id');

        $pjuMarkers = [];
        foreach ($groupedPjus as $trafoId => $items) {
            foreach ($items as $index => $item) {
                $pjuMarkers[] = [
                    'id' => $item->id,
                    'nomor' => (string) ($index + 1),
                    'lat' => (float) $item->trafo->latitude,
                    'lng' => (float) $item->trafo->longitude,
                    'total_siblings' => count($items),
                    'sibling_index' => $index,
                    'title' => $item->id_pelanggan ?? 'No-ID',
                    'status' => $item->status,
                    'kondisi' => $item->kondisi_lampu,
                    'trafo_kode' => $item->trafo->kode_trafo ?? '-',
                    'rayon' => $item->rayon->nama ?? '-',
                ];
            }
        }

        $trafoMarkers = $pjus->pluck('trafo')->unique('id')->map(function ($item) {
            return [
                'id' => $item->id,
                'kode' => $item->id_gardu,
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
            ];
        })->values();

        $rayons = Rayon::orderBy('nama')->get();
        return view('pages.maps.index', compact('pjuMarkers', 'trafoMarkers', 'rayons'));
    }

    /**
     * Maps Per Kecamatan/Kelurahan.
     */
    public function indexArea(Request $request)
    {
        $kecamatans = Trafo::select('kecamatan')
            ->whereNotNull('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $kelurahans = [];
        if ($request->filled('kecamatan')) {
            $kelurahans = Trafo::select('kelurahan')
                ->where('kecamatan', $request->kecamatan)
                ->whereNotNull('kelurahan')
                ->distinct()
                ->orderBy('kelurahan')
                ->pluck('kelurahan');
        }

        $query = PJU::with(['trafo', 'rayon']);

        $query->whereHas('trafo', function ($q) use ($request) {
            $q->whereNotNull('latitude')->whereNotNull('longitude');

            if ($request->filled('kecamatan')) {
                $q->where('kecamatan', $request->kecamatan);
            }
            if ($request->filled('kelurahan')) {
                $q->where('kelurahan', $request->kelurahan);
            }
        });

        $pjus = $query->get();
        $groupedPjus = $pjus->groupBy('trafo_id');

        $pjuMarkers = [];
        foreach ($groupedPjus as $trafoId => $items) {
            foreach ($items as $index => $item) {
                $pjuMarkers[] = [
                    'id' => $item->id,
                    'nomor' => (string) ($index + 1),
                    'lat' => (float) $item->trafo->latitude,
                    'lng' => (float) $item->trafo->longitude,
                    'total_siblings' => count($items),
                    'sibling_index' => $index,
                    'title' => $item->id_pelanggan ?? 'No-ID',
                    'status' => $item->status,
                    'kondisi' => $item->kondisi_lampu,
                    'trafo_kode' => $item->trafo->id_gardu ?? '-',
                    'rayon' => $item->rayon->nama ?? '-',
                    'kecamatan' => $item->trafo->kecamatan ?? '-',
                    'kelurahan' => $item->trafo->kelurahan ?? '-',
                ];
            }
        }

        $trafoMarkers = $pjus->pluck('trafo')->unique('id')->map(function ($item) {
            return [
                'id' => $item->id,
                'kode' => $item->kode_trafo,
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
            ];
        })->values();

        return view('pages.maps.area', compact('pjuMarkers', 'trafoMarkers', 'kecamatans', 'kelurahans'));
    }

    /**
     * Maps Per ID Pelanggan.
     */
    public function indexIdpel(Request $request)
    {
        $query = PJU::with(['trafo:id,id_gardu,latitude,longitude', 'rayon:id,nama']);

        if ($request->filled('rayon_id')) {
            $query->where('rayon_id', $request->rayon_id);
        }
        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', 'like', '%' . $request->id_pelanggan . '%');
        }

        $pjus = $query->whereHas('trafo', function ($q) {
            $q->whereNotNull('latitude')->whereNotNull('longitude');
        })->get();

        $groupedPjus = $pjus->groupBy('trafo_id');

        $pjuMarkers = [];
        foreach ($groupedPjus as $trafoId => $items) {
            foreach ($items as $index => $item) {
                $pjuMarkers[] = [
                    'id' => $item->id,
                    'nomor' => (string) ($index + 1),
                    'lat' => (float) $item->trafo->latitude,
                    'lng' => (float) $item->trafo->longitude,
                    'total_siblings' => count($items),
                    'sibling_index' => $index,
                    'title' => $item->id_pelanggan ?? 'No-ID',
                    'status' => $item->status,
                    'kondisi' => $item->kondisi_lampu,
                    'trafo_kode' => $item->trafo->id_gardu ?? '-',
                    'rayon' => $item->rayon->nama ?? '-',
                ];
            }
        }

        $trafoMarkers = $pjus->pluck('trafo')->unique('id')->map(function ($item) {
            return [
                'id' => $item->id,
                'kode' => $item->id_gardu,
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
            ];
        })->values();

        $rayons = Rayon::orderBy('nama')->get();
        return view('pages.maps.idpel', compact('pjuMarkers', 'trafoMarkers', 'rayons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
