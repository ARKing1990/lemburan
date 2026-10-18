<!DOCTYPE html>
<html>
<head>
    <title>Form Lembur</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }

        /* Layout Header dengan Tabel agar stabil di PDF */
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }
        .company-name { font-weight: bold; font-size: 12px; margin-bottom: 2px; }
        .address { font-size: 10px; margin-bottom: 20px; }
        .form-title { text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline; }

        /* Logo di kanan atas */
        .logo-container { text-align: right; }
        .logo-img { height: 50px; } /* Sesuaikan tinggi logo */

        /* Info Pegawai (Kiri & Kanan) */
        .info-table { width: 100%; margin-bottom: 10px; font-weight: bold; }
        .info-table td { padding-bottom: 2px; }

        /* Tabel Utama */
        .main-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .main-table th, .main-table td { border: 1px solid black; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .main-table th { background-color: #ffffff; font-weight: bold; height: 30px; }
        .text-left { text-align: left !important; }

        /* Kolom Tanda Tangan */
        .signature-table { width: 100%; margin-top: 30px; border: none; }
        .signature-table td { text-align: center; vertical-align: top; width: 33%; }
        .sign-space { height: 80px; }
        .sign-name { text-decoration: underline; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-name">PT. SOLUSI AKSESINDO PRATAMA</div>
                <div class="address">Jl. Gunawarman No. 67, Jakarta 12180</div>
            </td>
            <td class="logo-container">
                <img src="{{ public_path('img/logo.png') }}" class="logo-img" alt="Logo netZAP">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="form-title">Form Lembur</td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td width="15%">Nama</td>
            <td width="40%">: {{ $user->name }}</td>
            <td width="15%">Departement</td>
            <td>: SI</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>: {{ $periodeLabel }}</td>
            <td>Jabatan</td>
            <td>: Installer</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" width="5%">NO</th>
                <th rowspan="2" width="20%">TANGGAL</th>
                <th colspan="2">JAM</th>
                <th rowspan="2" width="10%">JUMLAH<br>JAM</th>
                <th rowspan="2">KETERANGAN</th>
                <th colspan="2">DITUGASKAN</th>
            </tr>
            <tr>
                <th width="10%">Mulai</th>
                <th width="10%">Selesai</th>
                <th width="12%">OLEH</th>
                <th width="10%">PARAF</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataLembur as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ \Carbon\Carbon::parse($row->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->jam_selesai)->format('H:i') }}</td>
                <td>{{ $row->jumlah_jam }} Jam</td>
                <td class="text-left">{{ $row->keterangan }}</td>
                <td>{{ $row->ditugaskan_oleh }}</td>
                <td></td> </tr>
            @endforeach

            @for($i = 0; $i < (15 - count($dataLembur)); $i++)
            <tr>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                <td style="font-weight: bold;">{{ $totalJam }} Jam</td>
                <td colspan="3" style="background-color: #ccc;"></td> </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td colspan="3" style="text-align: left; padding-left: 0; padding-bottom: 10px;">
                Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>
                Diajukan oleh :
                <div class="sign-space"></div>
                <div class="sign-name">( {{ $user->name }} )</div>
            </td>
            <td>
                Disetujui oleh :
                <div class="sign-space"></div>
                <div class="sign-name">( ............................ )</div>
            </td>
            <td>
                Diketahui oleh :
                <div class="sign-space"></div>
                <div class="sign-name">( ............................ )</div>
            </td>
        </tr>
    </table>

</body>
</html>
