@extends('layouts.adminlte')

@section('title', 'Buat Laporan')
@section('header_title', 'Buat Laporan Kegiatan Baru')

@section('content')
    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Dasar</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Judul Laporan</label>
                            <input type="text" name="judul_laporan" class="form-control"
                                placeholder="Cth: Report Bandwidth Event X" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Event</label>
                            <input type="text" name="nama_event" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-4"><i class="fas fa-save"></i> SIMPAN
                            LAPORAN</button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Isi Laporan (Poin-Poin)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPoint()">
                                <i class="fas fa-plus"></i> Tambah Poin
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="points-container">
                        <div class="point-item card p-3 mb-3 border" id="point-0">
                            <div class="d-flex justify-content-between">
                                <h5><span class="badge badge-info">Poin 1</span></h5>
                            </div>

                            <div class="form-group mt-2">
                                <label>Deskripsi Poin</label>
                                <textarea name="items[0][deskripsi]" class="form-control" rows="3"
                                    placeholder="Jelaskan apa yang dikerjakan pada poin ini..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Upload Foto Dokumentasi (Bisa pilih banyak)</label>
                                <div class="custom-file">
                                    <input type="file" name="items[0][fotos][]" class="custom-file-input" multiple
                                        accept="image/*">
                                    <label class="custom-file-label">Pilih Foto...</label>
                                </div>
                                <small class="text-muted">*Tahan CTRL untuk memilih banyak foto sekaligus.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        let pointIndex = 0;

        function addPoint() {
            pointIndex++;

            let html = `
            <div class="point-item card p-3 mb-3 border" id="point-${pointIndex}">
                <div class="d-flex justify-content-between">
                    <h5><span class="badge badge-info">Poin ${pointIndex + 1}</span></h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removePoint(${pointIndex})"><i class="fas fa-trash"></i></button>
                </div>

                <div class="form-group mt-2">
                    <label>Deskripsi Poin</label>
                    <textarea name="items[${pointIndex}][deskripsi]" class="form-control" rows="3" placeholder="Deskripsi..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Upload Foto Dokumentasi</label>
                    <div class="custom-file">
                        <input type="file" name="items[${pointIndex}][fotos][]" class="custom-file-input" multiple accept="image/*">
                        <label class="custom-file-label">Pilih Foto...</label>
                    </div>
                </div>
            </div>
        `;

            // Tambahkan ke container
            document.getElementById('points-container').insertAdjacentHTML('beforeend', html);

            // Re-init custom file input (AdminLTE bug fix)
            bsCustomFileInput.init();
        }

        function removePoint(index) {
            document.getElementById(`point-${index}`).remove();
        }

        // Init file input style
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
@endpush
