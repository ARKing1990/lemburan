@extends('layouts.adminlte')

@section('title', 'Edit Barang')
@section('header_title', 'Edit Master Data & Serial Number')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> Edit Informasi Utama</h3>
                </div>
                <form action="{{ route('gudang.barang.update', $barang->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control font-weight-bold"
                                        value="{{ $barang->nama_barang }}" required>
                                    <small class="text-muted">Perubahan nama akan berlaku untuk semua stok barang
                                        ini.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <select name="satuan" class="form-control" required>
                                        @foreach (['Unit', 'Pcs', 'Box', 'Roll', 'Meter', 'Pack'] as $sat)
                                            <option value="{{ $sat }}"
                                                {{ $barang->satuan == $sat ? 'selected' : '' }}>{{ $sat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning float-right">
                            <i class="fas fa-save"></i> Simpan Perubahan Nama
                        </button>
                        <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-barcode"></i> Daftar Serial Number ({{ $barang->stok }} Unit)
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Serial Number (SN)</th>
                                <th>Status Saat Ini</th>
                                <th style="width: 40%">Aksi (Koreksi SN)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barang->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-light border" style="font-size: 1rem">
                                            {{ $detail->serial_number }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($detail->status == 'Tersedia')
                                            <span class="badge badge-success">Ada di Gudang</span>
                                        @else
                                            <span class="badge badge-danger">Sedang Dipakai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('gudang.barang.update_sn', $detail->id) }}" method="POST"
                                            class="d-flex align-items-center">
                                            @csrf
                                            @method('PUT')

                                            <div class="input-group input-group-sm">
                                                <input type="text" name="serial_number" class="form-control"
                                                    value="{{ $detail->serial_number }}" required>
                                                <span class="input-group-append">
                                                    <button type="submit" class="btn btn-info btn-flat"
                                                        title="Update SN ini">
                                                        <i class="fas fa-save"></i> Ubah
                                                    </button>
                                                </span>
                                            </div>

                                            <a href="{{ route('gudang.barang.delete_sn', $detail->id) }}"
                                                class="btn btn-danger btn-sm ml-2"
                                                onclick="return confirm('Hapus unit SN ini? Stok master akan berkurang 1. Lakukan ini hanya jika stok fisik memang tidak ada.')"
                                                title="Hapus Unit">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i>Barang ini tidak memiliki Serial Number (Tipe Non-Elektronik atau Stok
                                            Kosong).</i>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
