@extends('layouts.adminlte')

@section('title', 'Daftar Laporan')
@section('header_title', 'Arsip Laporan Kegiatan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Laporan Tersimpan</h3>
            <div class="card-tools">
                <a href="{{ route('laporan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Buat Laporan Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table id="tabel-laporan" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th>Judul Laporan</th>
                        <th>Event / Kegiatan</th>
                        <th>Dibuat Oleh</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $index => $lap)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($lap->tanggal_laporan)->translatedFormat('d F Y') }}</td>
                            <td class="font-weight-bold">{{ $lap->judul_laporan }}</td>
                            <td>
                                {{ $lap->nama_event }}
                                <br>
                                <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $lap->lokasi }}</small>
                            </td>
                            <td>{{ $lap->user->name ?? 'User Terhapus' }}</td>
                            <td>
                                <a href="{{ route('laporan.show', $lap->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#tabel-laporan").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [
                    [1, "desc"]
                ] // Urutkan berdasarkan tanggal terbaru
            });
        });
    </script>
@endpush
