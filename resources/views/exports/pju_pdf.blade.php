<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data PJU</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .badge {
            font-size: 8pt;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .rusak {
            color: red;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Data Penerangan Jalan Umum (PJU)</h2>
        <p>Dicetak Tanggal: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pel</th>
                <th>Lokasi (Area/Rayon)</th>
                <th>Alamat</th>
                <th>Spek</th>
                <th>Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pjus as $index => $pju)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pju->id_pelanggan ?? '-' }}</td>
                    <td>{{ $pju->area->nama ?? '' }} / {{ $pju->rayon->nama ?? '' }}</td>
                    <td>{{ Str::limit($pju->alamat, 50) }}</td>
                    <td>{{ $pju->jenis_lampu }} {{ $pju->watt }}W</td>
                    <td class="{{ $pju->kondisi_lampu == 'rusak' ? 'rusak' : '' }}">
                        {{ ucfirst($pju->kondisi_lampu) }}
                    </td>
                    <td>{{ ucfirst($pju->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>