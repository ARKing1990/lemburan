@extends('layouts.adminlte')

@section('title', 'Stok Gudang')
@section('header_title', 'Data Inventaris Gudang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Barang Saat Ini</h3>
            <div class="card-tools">
                <a href="{{ route('gudang.barang.create') }}" class="btn btn-success btn-sm mr-1">
                    <i class="fas fa-plus"></i> Barang Baru
                </a>

                <a href="{{ route('gudang.laporan') }}" class="btn btn-info btn-sm mr-1">
                    <i class="fas fa-file-alt"></i> Lihat Riwayat Event
                </a>

                <a href="{{ route('gudang.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-exchange-alt"></i> Catat Transaksi
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table id="tabel-gudang" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode / SN</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-light border">{{ $item->kode_barang ?? '-' }}</span></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>
                                @if ($item->stok <= 5)
                                    <span class="badge badge-danger" style="font-size: 1rem">{{ $item->stok }}</span>
                                @else
                                    <span class="badge badge-success" style="font-size: 1rem">{{ $item->stok }}</span>
                                @endif
                            </td>
                            <td>{{ $item->satuan }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('gudang.show', $item->id) }}" class="btn btn-info btn-sm"
                                        title="Riwayat">
                                        <i class="fas fa-history"></i>
                                    </a>

                                    <a href="{{ route('gudang.barang.edit', $item->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('gudang.barang.destroy', $item->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus barang ini selamanya? Riwayat transaksi juga akan terhapus!')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
            $("#tabel-gudang").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endpush
