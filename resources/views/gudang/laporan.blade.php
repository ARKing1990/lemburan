@extends('layouts.adminlte')

@section('title', 'Laporan Gudang')
@section('header_title', 'Laporan Transaksi Harian')

@section('content')
    <div class="card collapsed-card">
        <div class="card-header bg-light">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Periode</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <form action="{{ route('gudang.laporan') }}" method="GET" class="row">
                <div class="col-md-4">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Tampilkan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    @forelse($groupedData as $key => $items)
        @php
            // Pecah Key hasil grouping
            $parts = explode('|', $key);
            $tanggal = \Carbon\Carbon::parse($parts[0])->translatedFormat('d F Y');
            $event = $parts[1]; // Ini sekarang adalah "Nama Event Bersih"
            $jenis = $parts[2];
            $pic = $parts[3];

            $cardClass = $jenis == 'Keluar' ? 'card-danger' : 'card-success';
            $icon = $jenis == 'Keluar' ? 'fa-arrow-up' : 'fa-arrow-down';

            // Hitung total item dalam event ini
            $totalItem = $items->sum('jumlah');
        @endphp

        <div class="card {{ $cardClass }} card-outline collapsed-card">
            <div class="card-header">
                <div class="user-block">
                    <span class="username" style="margin-left: 0px; font-size: 1.1rem; cursor: pointer"
                        data-card-widget="collapse">
                        <i class="fas {{ $icon }} mr-2"></i> {{ $event }}
                        <span class="badge badge-light ml-2 border">{{ $totalItem }} Item</span>
                    </span>
                    <span class="description" style="margin-left: 0px">
                        <i class="far fa-calendar-alt"></i> {{ $tanggal }} &nbsp; | &nbsp;
                        <i class="fas fa-user"></i> PIC: {{ $pic }}
                    </span>
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="card-body p-0" style="display: none;">
                <table class="table table-sm table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="padding-left: 20px">Nama Barang</th>
                            <th>Serial Number (SN)</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td style="padding-left: 20px">{{ $item->barang->nama_barang }}</td>
                                <td>
                                    @if ($item->detail_barang)
                                        <span class="badge badge-warning">{{ $item->detail_barang->serial_number }}</span>
                                    @else
                                        <span class="text-muted text-sm font-italic">Non-SN</span>
                                    @endif
                                </td>
                                <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                                <td>{{ $item->kondisi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center mt-3">
            <h5><i class="icon fas fa-info"></i> Belum ada transaksi</h5>
            Tidak ada data pada periode {{ $startDate }} s/d {{ $endDate }}.
        </div>
    @endforelse

@endsection
