<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OfficerDetailExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;
    protected $user;

    public function __construct($query, $user)
    {
        $this->query = $query;
        $this->user = $user;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($row): array
    {
        return [
            $this->user->name,
            $this->user->rayon->nama ?? '-',
            $row->trafo->id_gardu ?? 'Tanpa Gardu',
            $row->trafo->alamat ?? '-',
            $row->total_input,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Petugas',
            'Rayon',
            'ID Gardu (Trafo)',
            'Lokasi Gardu',
            'Jumlah Input PJU',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}