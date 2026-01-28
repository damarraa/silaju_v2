<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lembar Pengesahan Gardu</title>
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        /* Layout Info */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .label {
            width: 15%;
            font-weight: bold;
        }

        .sep {
            width: 2%;
        }

        .val {
            width: 83%;
        }

        /* Peta Box */
        .map-container {
            width: 100%;
            height: 450px;
            border: 2px solid #000;
            margin-bottom: 20px;
            overflow: hidden;
            text-align: center;
        }

        .map-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Tabel Data */
        table.bordered {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.bordered th,
        table.bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        table.bordered th {
            background-color: #f0f0f0;
        }

        /* Tabel Tanda Tangan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .signature-table th,
        .signature-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .signature-table td {
            height: 70px;
            vertical-align: bottom;
        }

        /* Ruang ttd */
    </style>
</head>

<body>

    @foreach($groupedPjus as $trafoId => $items)
        @php
            $trafo = $trafos[$trafoId] ?? null;
            if (!$trafo)
                continue;

            $totalTitik = $items->count();
            $totalWatt = $items->sum('watt');
            $statusDominan = $items->groupBy('status')->sortDesc()->keys()->first();

            // Setup URL
            $center = "{$trafo->latitude},{$trafo->longitude}";
            $zoom = 17;
            $size = "640x450";
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            $markers = "";
            $markers .= "&markers=color:red|label:T|{$trafo->latitude},{$trafo->longitude}";
            foreach ($items->take(30) as $pju) {
                $markers .= "&markers=color:blue|size:tiny|{$pju->latitude},{$pju->longitude}";
            }

            $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?center={$center}&zoom={$zoom}&size={$size}&maptype=roadmap{$markers}&key={$apiKey}";

            // --- UPDATE: MENGGUNAKAN LARAVEL HTTP CLIENT ---
            $base64Map = null;
            $errorMsg = '';

            try {
                // Menggunakan Facade Http (Pastikan namespace di-import atau gunakan full path)
                // Ini lebih handal daripada file_get_contents
                $response = \Illuminate\Support\Facades\Http::get($mapUrl);

                if ($response->successful()) {
                    $base64Map = 'data:image/png;base64,' . base64_encode($response->body());
                } else {
                    // Tangkap pesan error dari Google (misal: "The provided API key is invalid")
                    $errorMsg = $response->body();
                    \Illuminate\Support\Facades\Log::error('Google Static Map Error: ' . $errorMsg);
                }
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                \Illuminate\Support\Facades\Log::error('Connection Error: ' . $e->getMessage());
            }
        @endphp

        <div class="{{ !$loop->last ? 'page-break' : '' }}">

            <div class="header">
                PT PLN (PERSERO) WRKR<br>
                {{ $trafo->area->nama }} - {{ strtoupper($trafo->rayon->nama ?? '-') }}
            </div>

            <div class="title">LEMBAR PENGESAHAN</div>

            <table class="info-table">
                <tr>
                    <td class="label">ID GARDU</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $trafo->id_gardu }}</td>
                </tr>
                <tr>
                    <td class="label">ALAMAT</td>
                    <td class="sep">:</td>
                    <td class="val">{{ Str::limit($trafo->alamat, 80) }}</td>
                </tr>
                <tr>
                    <td class="label">DAYA</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $trafo->daya ?? '-' }} VA</td>
                </tr>
                <tr>
                    <td class="label">KOORDINAT</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $trafo->latitude }}, {{ $trafo->longitude }}</td>
                </tr>
            </table>

            <div class="map-container">
                @if($base64Map)
                    <img src="{{ $base64Map }}" class="map-img" alt="Peta Lokasi">
                @else
                    <div style="padding-top: 150px; color: red;">
                        <p style="font-weight:bold; font-size:14px;">Gagal Memuat Peta</p>
                        {{-- Tampilkan error spesifik agar ketahuan salahnya dimana --}}
                        <p style="font-size: 10px; margin-top:5px;">
                            Error dari Google: {{ Str::limit(strip_tags($errorMsg), 100) }}
                        </p>
                        <p style="font-size: 9px; color: #555;">
                            Tips: Cek Log Laravel atau pastikan API Key tidak di-restrict ke HTTP Referrer.
                        </p>
                    </div>
                @endif
            </div>

            <table class="bordered">
                <thead>
                    <tr>
                        <th width="40%">STATUS DOMINAN</th>
                        <th width="30%">JUMLAH TITIK</th>
                        <th width="30%">TOTAL DAYA (WATT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ strtoupper(str_replace('_', ' ', $statusDominan)) }}</td>
                        <td>{{ $totalTitik }}</td>
                        <td>{{ number_format($totalWatt, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="signature-table">
                <thead>
                    <tr>
                        <th width="25%">URAIAN</th>
                        <th width="45%">NAMA</th>
                        <th width="30%">PARAF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; vertical-align: middle; padding-left: 10px;">Disurvey / Digambar</td>
                        <td style="vertical-align: bottom;">{{ $items->first()->user->name ?? 'Petugas' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; vertical-align: middle; padding-left: 10px;">Diperiksa</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; vertical-align: middle; padding-left: 10px;">Disetujui</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 10px; font-size: 9px; font-style: italic; text-align: right;">
                Dicetak pada: {{ date('d-m-Y H:i') }}
            </div>

        </div>
    @endforeach

</body>

</html>