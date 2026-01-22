<?php

namespace App\Http\Controllers\Wilayah;

use App\Http\Controllers\Controller;
use App\Models\Rayon;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Helper Dynamic Dropdown
     */
    public function getRayonsByArea($areaId)
    {
        $rayons = Rayon::where('area_id', $areaId)
            ->select('id', 'nama', 'kode_rayon')
            ->orderBy('nama')
            ->get();
        return response()->json($rayons);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
