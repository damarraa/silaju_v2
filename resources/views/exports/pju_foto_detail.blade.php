<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Meterisasi PJU</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 20px 20px 30px 20px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
        }

        h1,
        h2,
        p {
            margin: 0;
            padding: 0;
        }

        /* ===== HEADER DOKUMEN ===== */
        .doc-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .doc-header h1 {
            font-size: 14px;
            font-weight: bold;
        }

        .doc-header h2 {
            font-size: 12px;
        }

        .doc-header p {
            font-size: 10px;
        }

        /* ===== INFO ===== */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 4px;
            font-size: 9px;
            vertical-align: top;
        }

        /* ===== TABEL DATA ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        table.data th {
            background-color: #eaeaea;
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
        }

        table.data td {
            white-space: normal;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /* ===== FOTO ===== */
        .foto {
            width: 60px;
            height: 45px;
            object-fit: cover;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            width: 100%;
        }

        .signature {
            width: 30%;
            float: right;
            text-align: center;
            font-size: 9px;
        }

        .clear {
            clear: both;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    @php
        // Ambil data pertama untuk Header
        // Gunakan optional helper (?) agar tidak error jika data kosong
        $firstItem = $pjus->first();

        $areaName = $firstItem?->area->nama ?? 'Semua Area';
        $rayonName = $firstItem?->rayon->nama ?? 'Semua Rayon';
        $userName = $firstItem?->user->name ?? 'Administrator';

        // Perbaikan: Ambil ID Gardu dari $firstItem, bukan $pju
        $sampleGardu = $firstItem?->trafo->id_gardu ?? '-';

        // Tanggal Cetak (Hari ini)
        $tanggalCetak = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
    @endphp

    <div class="doc-header">
        <h1>PT PLN (Persero)</h1>
        <h2>{{ $areaName }} – {{ $rayonName }}</h2>
        <p>Laporan Meterisasi PJU</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="12%">Rayon</td>
            <td width="1%">:</td>
            <td width="37%">{{ $rayonName }}</td>

            <td width="12%">Tanggal</td>
            <td width="1%">:</td>
            <td width="37%">{{ $tanggalCetak }}</td>
        </tr>
        <tr>
            <td>ID Trafo</td>
            <td>:</td>
            <td>{{ $sampleGardu }}</td>

            <td>Petugas</td>
            <td>:</td>
            <td>{{ $userName }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">Foto</th>
                <th width="8%">ID Gardu</th>
                <th width="10%">ID Pelanggan</th>
                <th width="20%">Alamat</th>
                <th width="10%">Koordinat</th>
                <th width="8%">Status</th>
                <th width="8%">Daya / Watt Total</th>
                <th width="10%">Jenis Lampu / Merk</th>
                <th width="7%">Kondisi</th>
                <th width="8%">Peruntukan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pjus as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        @php
                            $imagePath = $item->evidence ? public_path('storage/' . $item->evidence) : null;
                        @endphp

                        @if($imagePath && file_exists($imagePath))
                            <img src="{{ $imagePath }}" class="foto">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->trafo->id_gardu ?? '-' }}</td>

                    <td style="text-align: center;">{{ $item->id_pelanggan ?? '-' }}</td>

                    <td class="text-left">
                        {{ Str::limit($item->alamat, 50) }}<br>
                        <small><i>{{ $item->kelurahan }}, {{ $item->kecamatan }}</i></small>
                    </td>

                    <td style="text-align: center;">
                        {{ number_format($item->latitude, 5) }}<br>
                        {{ number_format($item->longitude, 5) }}
                    </td>

                    <td style="text-align: center;">
                        @if($item->status == 'meterisasi')
                            Meterisasi
                        @elseif($item->status == 'non_meterisasi')
                            Non-Meter
                        @else
                            {{ ucfirst($item->status) }}
                        @endif
                    </td>

                    <td style="text-align: center;">
                        <strong>D:</strong> {{ $item->daya ?? '-' }}<br>
                        <strong>W:</strong> {{ $item->watt ?? '-' }}
                    </td>

                    <td style="text-align: center;">
                        {{ $item->jenis_lampu ?? '-' }}<br>
                        {{ $item->merk_lampu ?? '-' }}
                    </td>

                    <td style="text-align: center;">
                        @if($item->kondisi_lampu == 'baik')
                            Baik
                        @else
                            <span style="color: red;">Rusak</span>
                        @endif
                    </td>

                    <td style="text-align: center;">{{ ucfirst($item->peruntukan) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="padding: 20px; text-align: center;">Tidak ada data tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>{{ $rayonName }}, {{ $tanggalCetak }}</p>
            <p>Dibuat Oleh,</p>
            <br><br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">{{ $userName }}</p>
            <p>Petugas Lapangan</p>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>