<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kinerja Petugas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>Laporan Rincian Input Data Lapangan</h3>
    </div>

    <div class="meta">
        <strong>Nama Petugas:</strong> {{ $user->name }} <br>
        <strong>Email:</strong> {{ $user->email }} <br>
        <strong>Unit Rayon:</strong> {{ $user->rayon->nama ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">ID Gardu</th>
                <th style="width: 60%">Alamat Gardu</th>
                <th style="width: 15%" class="text-right">Jumlah Input</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($details as $index => $row)
                @php $grandTotal += $row->total_input; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->trafo->id_gardu ?? 'Tanpa Gardu' }}</td>
                    <td>{{ $row->trafo->alamat ?? '-' }}</td>
                    <td class="text-right">{{ $row->total_input }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL INPUT</td>
                <td class="text-right" style="font-weight: bold;">{{ $grandTotal }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>