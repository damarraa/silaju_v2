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

        h1, h2, p {
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
            display: table-header-group; /* header ulang tiap halaman */
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
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="doc-header">
        <h1>PT PLN (Persero)</h1>
        <h2>UP3 Rengat – ULP Taluk Kuantan</h2>
        <p>Laporan Meterisasi PJU</p>
    </div>

    <!-- INFO -->
    <table class="info-table">
        <tr>
            <td width="12%">Rayun</td>
            <td width="1%">:</td>
            <td width="37%">{{rayun}}</td>

            <td width="12%">Tanggal</td>
            <td width="1%">:</td>
            <td width="37%">{{tanggal}}</td>
        </tr>
        <tr>
            <td>ID Trafo</td>
            <td>:</td>
            <td>{{id_trafo}}</td>

            <td>Petugas</td>
            <td>:</td>
            <td>{{petugas}}</td>
        </tr>
    </table>

    <!-- TABEL DATA -->
    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>ID Gardu</th>
                <th>ID Pelanggan</th>
                <th>Alamat</th>
                <th>Koordinat</th>
                <th>Status Meter</th>
                <th>Daya / Watt Total</th>
                <th>Jenis Lampu / Merk</th>
                <th>Kondisi</th>
                <th>Peruntukan</th>
            </tr>
        </thead>
        <tbody>
            {{rows}}
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <div class="signature">
            <p>Taluk Kuantan, {{tanggal}}</p>
            <br><br><br>
            <p><strong>{{petugas}}</strong></p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
