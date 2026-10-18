@extends('layouts.adminlte')

@section('title', 'Detail Barang')
@section('header_title', 'Kartu Stok Barang')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $barang->nama_barang }}</h3>
                    <p class="text-muted text-center">{{ $barang->kode_barang ?? 'Tanpa SN' }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Sisa Stok Saat Ini</b> <a class="float-right badge badge-success"
                                style="font-size: 1.2rem">{{ $barang->stok }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Satuan</b> <a class="float-right">{{ $barang->satuan }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Transaksi</b> <a class="float-right">{{ $barang->transaksi->count() }} Kali</a>
                        </li>
                    </ul>

                    <a href="{{ route('gudang.index') }}" class="btn btn-secondary btn-block"><b>Kembali ke Daftar</b></a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <h3 class="card-title p-2">Riwayat Keluar Masuk</h3>
                </div>
                <div class="card-body">
                    <table id="tabel-riwayat" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Serial Number (SN)</th>
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                                <th>Keterangan / Event</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang->transaksi as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if ($log->jenis == 'Masuk')
                                            <span class="badge badge-success"><i class="fas fa-arrow-down"></i> Masuk</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-arrow-up"></i> Keluar</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($log->detail_barang)
                                            <span class="badge badge-warning" style="font-size: 0.9rem">
                                                {{ $log->detail_barang->serial_number }}
                                            </span>
                                        @else
                                            <span class="text-muted font-italic">- (Non SN) -</span>
                                        @endif
                                    </td>

                                    <td class="font-weight-bold">{{ $log->jumlah }}</td>
                                    <td>
                                        @if ($log->kondisi == 'Rusak')
                                            <span class="text-danger font-weight-bold">Rusak</span>
                                        @elseif($log->kondisi == 'Hilang')
                                            <span class="text-dark font-weight-bold">Hilang</span>
                                        @else
                                            <span class="text-success">Baik</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->keterangan }}</td>
                                    <td><small>{{ $log->user->name ?? 'Deleted' }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#tabel-riwayat").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [
                    [0, "desc"]
                ] // Urutkan tanggal terbaru
            });
        });
    </script>
@endpush
