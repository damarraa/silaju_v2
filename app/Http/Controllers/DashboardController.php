<?php

namespace App\Http\Controllers;

use App\Models\PJU;
use App\Models\Rayon;
use App\Models\Trafo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Dashboard untuk Super Admin.
     * Global View.
     */
    public function admin()
    {
        $user = auth()->user();

        // --- 1. PREPARE QUERY BUILDER (BASE SCOPE) ---
        $pjuQuery = PJU::query();
        $trafoQuery = Trafo::query();
        $rayonQuery = Rayon::query();

        // --- 2. TERAPKAN FILTER BERDASARKAN ROLE ---

        // Jika Admin ULP, kunci data hanya untuk Rayon dia sendiri
        if ($user->hasRole('admin_ulp') && $user->rayon_id) {
            $pjuQuery->where('rayon_id', $user->rayon_id);
            // Asumsi Trafo juga punya rayon_id, jika tidak, skip filter ini atau relasikan
            $trafoQuery->where('rayon_id', $user->rayon_id);
            $rayonQuery->where('id', $user->rayon_id);
        }

        // (Opsional) Jika Admin UP3 atau Verifikator butuh filter khusus, tambahkan di sini.
        // Saat ini kita anggap mereka melihat Global seperti Super Admin.

        // --- 3. EKSEKUSI DATA (METRICS) ---
        $totalPju = $pjuQuery->count();
        $totalTrafo = $trafoQuery->count();
        // Clone query agar tidak merusak query utama saat nambah where
        $pjuRusak = (clone $pjuQuery)->where('kondisi_lampu', 'rusak')->count();
        $pjuIlegal = (clone $pjuQuery)->where('status', 'ilegal')->count();

        $persenRusak = $totalPju > 0 ? round(($pjuRusak / $totalPju) * 100, 1) : 0;

        // --- 4. DATA CHART: KONDISI PER RAYON ---
        // Kita ambil data rayon sesuai scope (All untuk Admin, Single untuk ULP)
        $rayons = $rayonQuery->withCount([
            'pjus as pju_baik_count' => function ($q) {
                $q->where('kondisi_lampu', 'baik');
            },
            'pjus as pju_rusak_count' => function ($q) {
                $q->where('kondisi_lampu', 'rusak');
            }
        ])->get();

        $chartRayonLabels = $rayons->pluck('nama')->toArray();
        $chartRayonBaik = $rayons->pluck('pju_baik_count')->toArray();
        $chartRayonRusak = $rayons->pluck('pju_rusak_count')->toArray();

        // --- 5. DATA CHART: STATUS METERISASI ---
        $statusCounts = (clone $pjuQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $chartStatusData = [
            $statusCounts['meterisasi'] ?? 0,
            $statusCounts['non_meterisasi'] ?? 0,
            $statusCounts['ilegal'] ?? 0
        ];

        // --- 6. RECENT ACTIVITY ---
        $recentPjus = (clone $pjuQuery)
            ->with(['rayon', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.admin.dashboard.index', compact(
            'totalPju',
            'totalTrafo',
            'pjuRusak',
            'pjuIlegal',
            'persenRusak',
            'chartRayonLabels',
            'chartRayonBaik',
            'chartRayonRusak',
            'chartStatusData',
            'recentPjus'
        ));
    }

    /**
     * Dashboard untuk User/Petugas.
     * Personal View.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole(['super_admin', 'admin_up3', 'admin_ulp', 'verifikator'])) {
            return redirect()->route('dashboard.admin');
        }

        $myTotalInput = PJU::where('user_id', $user->id)->count();
        $myInputToday = PJU::where('user_id', $user->id)->whereDate('created_at', today())->count();

        $myRecents = PJU::with('rayon')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.user.dashboard.index', compact('myTotalInput', 'myInputToday', 'myRecents'));
    }
}
