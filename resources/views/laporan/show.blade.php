@extends('layouts.adminlte')

@section('title', 'Detail Laporan')

@section('content')
    <div class="row no-print mb-3">
        <div class="col-12">
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary float-right">
                <i class="fas fa-print"></i> Download PDF / Print
            </button>
        </div>
    </div>

    <div class="print-area">

        <div class="cover-page">
            <div class="text-center" style="padding-top: 30%;">

                <div class="mb-5">
                    <img src="{{ asset('img/logo10.png') }}" alt="Halo 10 Logo"
                        style="height: 120px; width: auto; display: inline-block;">
                    <br>
                    <span class="text-muted font-weight-bold"
                        style="letter-spacing: 4px; font-size: 1rem; text-transform: uppercase;">Internet Organizer</span>
                </div>

                <div class="mt-5 mb-5">
                    <h1 class="font-weight-bold text-uppercase" style="font-size: 3rem; color: #000; line-height: 1.2;">
                        REPORT BANDWIDTH TRAFFIC<br>MONITORING
                    </h1>
                </div>

                <div class="mt-5">
                    <h4 class="font-weight-bold mb-4" style="font-size: 1.4rem;">Kepada:</h4>
                    <div style="display: inline-block; text-align: left;">
                        <table class="table table-borderless event-details" style="font-size: 1.4rem; margin-bottom: 0;">
                            <tr>
                                <td width="120" style="padding: 5px;"><strong>Event</strong></td>
                                <td width="20" style="padding: 5px;">:</td>
                                <td style="padding: 5px;">{{ $laporan->nama_event }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;"><strong>Lokasi</strong></td>
                                <td style="padding: 5px;">:</td>
                                <td style="padding: 5px;">{{ $laporan->lokasi }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;"><strong>Tanggal</strong></td>
                                <td style="padding: 5px;">:</td>
                                <td style="padding: 5px;">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <br><br>
                    <h4 class="font-weight-bold" style="font-size: 1.2rem;">Ditujukan kepada:</h4>
                    <h2 class="font-weight-bold mt-2" style="font-size: 2rem;">PT / Panitia Acara</h2>
                </div>
            </div>
        </div>

        @foreach ($laporan->items as $index => $item)
            <div class="landscape-page">

                <div style="width: 100%; text-align: center; margin-bottom: 20px;">
                    <h4 class="font-weight-bold m-0" style="color: #333; font-size: 1.5rem; text-transform: uppercase;">
                        PT. SOLUSI AKSESINDO PRATAMA
                    </h4>
                    <p class="m-0" style="font-size: 1rem;">
                        Jl. Gunawarman No 67 Kebayoran Baru Jakarta Selatan 12180 | Telp +6221-7396364
                    </p>
                    <div style="border-top: 3px solid #000; margin-top: 15px; margin-bottom: 20px; width: 100%;"></div>
                </div>

                <h3 class="font-weight-bold mb-4"
                    style="text-transform: capitalize; border-bottom: 1px dashed #ccc; padding-bottom: 10px; text-align: center;">
                    {{ $index + 1 }}. {{ $item->deskripsi }}
                </h3>

                @if ($item->images->count() > 0)
                    <div style="width: 100%; text-align: center;">
                        @foreach ($item->images as $img)
                            <div style="margin-bottom: 15px; display: inline-block; width: 100%; box-sizing: border-box;">
                                <div style="border: 1px solid #ddd; padding: 10px; background: white; border-radius: 5px;">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                        style="width: 100%; height: auto; max-height: 13cm; object-fit: contain;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light text-center text-muted p-5 border" style="margin-top: 50px;">
                        <h3><em>(Tidak ada dokumentasi gambar pada poin ini)</em></h3>
                    </div>
                @endif

            </div>
        @endforeach

    </div>

    <style>
        /* CSS RESET KHUSUS PRINT */
        @media print {

            /* Paksa Layout Kertas */
            @page {
                margin: 0;
                size: auto;
            }

            /* Halaman Landscape: Margin diset 1cm agar rapi */
            @page landscape-sheet {
                size: landscape;
                margin: 10mm;
            }

            /* Matikan elemen website AdminLTE */
            .no-print,
            .main-header,
            .main-sidebar,
            .main-footer {
                display: none !important;
            }

            /* --- INTINYA DISINI: RESET WIDTH & PADDING --- */
            /* Kita paksa semua container menjadi 100% dan hapus padding bawaan */
            html,
            body,
            .wrapper,
            .content-wrapper,
            .container-fluid,
            .card,
            .card-body {
                background: white !important;
                width: 100% !important;
                min-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            /* Pastikan Print Area bersih */
            .print-area {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }

        /* --- COVER PAGE (PORTRAIT) --- */
        .cover-page {
            width: 100%;
            height: 100vh;
            position: relative;
            break-after: page;
            page-break-after: always;
            background: white;
        }

        /* --- LANDSCAPE PAGE (CONTENT) --- */
        .landscape-page {
            /* Pemicu Landscape */
            page: landscape-sheet;

            /* Paksa pindah halaman tiap poin */
            break-after: page;
            page-break-after: always;

            /* Lebar Penuh */
            width: 100%;
            position: relative;
            background: white;
        }

        /* Hapus break di halaman terakhir */
        .landscape-page:last-child {
            break-after: auto;
            page-break-after: auto;
        }

        .print-area {
            font-family: 'Arial', sans-serif;
            color: #000;
        }
    </style>
@endsection
