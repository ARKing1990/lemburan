@extends('layouts.adminlte')

@section('title', 'Tambah Barang')
@section('header_title', 'Master Data Barang Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Input Barang</h3>
                </div>
                <form action="{{ route('gudang.barang.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label>Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control"
                                placeholder="Contoh: Kabel LAN Belden Cat6" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Satuan <span class="text-danger">*</span></label>
                                    <select name="satuan" class="form-control" required>
                                        <option value="Unit">Unit</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Box">Box</option>
                                        <option value="Roll">Roll</option>
                                        <option value="Meter">Meter</option>
                                        <option value="Pack">Pack</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stok Awal</label>
                                    <input type="number" name="stok" class="form-control" value="0" min="0"
                                        required>
                                    <small class="text-muted">Masukkan jumlah stok fisik saat ini.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary float-right">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
