<?php

namespace App\Exports;

use App\Models\PJU;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PJUExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($pju): array
    {
        return [
            $pju->id_pelanggan ?? 'Non-Meter',
            $pju->area->nama ?? '-',
            $pju->rayon->nama ?? '-',
            $pju->trafo->id_gardu ?? '-',
            $pju->alamat,
            $pju->jenis_lampu . ' - ' . $pju->watt . 'W',
            $pju->merk_lampu,
            $pju->kondisi_lampu,
            $pju->status,
            $pju->verification_status,
            $pju->created_at->format('d-m-Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID Pelanggan',
            'Area',
            'Rayon',
            'ID Gardu',
            'Alamat',
            'Spesifikasi',
            'Merk',
            'Kondisi',
            'Status Meter',
            'Verifikasi',
            'Tgl Input',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold Header
        ];
    }
}