@extends('layouts.adminlte')

@section('title', 'Transaksi Gudang')
@section('header_title', 'Catat Barang Masuk / Keluar')

@section('content')
    <div class="row">
        <div class="col-md-12">

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gudang.store') }}" method="POST" id="form-transaksi">
                @csrf

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Dasar</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Transaksi</label>
                                    <select name="jenis" class="form-control font-weight-bold" id="jenis_transaksi"
                                        required>
                                        <option value="Keluar">Barang Keluar (Ambil stok dari Gudang)</option>
                                        <option value="Masuk_Kembali">Barang Kembali (Retur dari Event)</option>
                                        <option value="Masuk_Baru">Barang Baru (Beli / Tambah Aset)</option>
                                    </select>
                                    <small class="text-muted" id="hint-jenis">Mode: Memilih SN yang sudah ada di
                                        stok.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Keterangan / Nama Event</label>
                                    <input type="text" name="keterangan_event" class="form-control"
                                        placeholder="Contoh: Instalasi Event Netflix" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Barang</h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped text-nowrap" id="tabel-keranjang">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30%">Nama Barang</th>
                                    <th width="35%">Detail Serial Number (SN)</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="15%">Kondisi</th>
                                    <th width="5%"><i class="fas fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select name="barang_id[]" class="form-control select-barang" required
                                            onchange="cekSN(this)">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach ($barangs as $b)
                                                <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok Total:
                                                    {{ $b->stok }} {{ $b->satuan }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="sn-wrapper">
                                            <input type="text" class="form-control bg-light"
                                                placeholder="Pilih barang dahulu..." disabled>
                                        </div>
                                        <input type="hidden" class="row-index" value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah[]" class="form-control qty-input" min="1"
                                            value="1" required>
                                    </td>
                                    <td>
                                        <select name="kondisi[]" class="form-control">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak">Rusak</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row" disabled><i
                                                class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        <button type="button" class="btn btn-success" id="add-row">
                            <i class="fas fa-plus-circle"></i> Tambah Barang Lain
                        </button>
                        <small class="text-muted ml-2 font-italic">*Pastikan semua SN terpilih sebelum menyimpan.</small>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                            <i class="fas fa-save"></i> SIMPAN TRANSAKSI
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Variable global untuk tracking index name array
        let rowIndexCounter = 0;

        document.addEventListener('DOMContentLoaded', function() {

            // 1. Listener Ubah Jenis Transaksi
            document.getElementById('jenis_transaksi').addEventListener('change', function() {
                let jenis = this.value;
                let hint = document.getElementById('hint-jenis');

                if (jenis == 'Keluar') {
                    hint.innerText = "Mode: Memilih SN yang sudah ada di stok.";
                    hint.className = "text-muted";
                } else {
                    hint.innerText = "Mode: Menginput SN baru (jika ada).";
                    hint.className = "text-info font-weight-bold";
                }

                // Reset semua baris karena logika input berubah total
                if (confirm('Mengubah jenis transaksi akan mereset inputan barang. Lanjutkan?')) {
                    resetTable();
                } else {
                    // Balikkan value jika cancel
                    this.value = (jenis == 'Keluar') ? 'Masuk' : 'Keluar';
                }
            });

            // 2. Listener Tambah Baris
            document.getElementById('add-row').addEventListener('click', function() {
                let tableBody = document.querySelector('#tabel-keranjang tbody');
                let firstRow = tableBody.rows[0];
                let newRow = firstRow.cloneNode(true);

                // Increment Index Counter
                rowIndexCounter++;

                // Reset Value di baris baru
                newRow.querySelector('select.select-barang').value = "";
                newRow.querySelector('.sn-wrapper').innerHTML =
                    '<input type="text" class="form-control bg-light" placeholder="Pilih barang dahulu..." disabled>';
                newRow.querySelector('.qty-input').value = "1";
                newRow.querySelector('.qty-input').readOnly = false;
                newRow.querySelector('.remove-row').disabled = false;

                // Update hidden index
                newRow.querySelector('.row-index').value = rowIndexCounter;

                tableBody.appendChild(newRow);
            });

            // 3. Listener Hapus Baris
            document.querySelector('#tabel-keranjang').addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    e.target.closest('tr').remove();
                }
            });
        });

        // --- FUNGSI UTAMA: CEK SERIAL NUMBER ---
        function cekSN(selectElement) {
            let barangId = selectElement.value;
            let row = selectElement.closest('tr');
            let jenisTransaksi = document.getElementById('jenis_transaksi').value;
            let snWrapper = row.querySelector('.sn-wrapper');
            let qtyInput = row.querySelector('.qty-input');

            let allRows = Array.from(document.querySelectorAll('#tabel-keranjang tbody tr'));
            let thisRowIndex = allRows.indexOf(row);

            if (!barangId) {
                snWrapper.innerHTML = '<input type="text" class="form-control bg-light" disabled>';
                return;
            }

            // Tentukan API Status berdasarkan Jenis Transaksi
            let apiStatus = '';
            if (jenisTransaksi == 'Keluar') {
                apiStatus = 'Tersedia'; // Ambil yg ada di gudang
            } else if (jenisTransaksi == 'Masuk_Kembali') {
                apiStatus = 'Terpakai'; // Ambil yg lagi dipinjam/diluar
            }

            // LOGIKA 1: Jika Keluar atau Kembali (Pakai Dropdown)
            if (jenisTransaksi == 'Keluar' || jenisTransaksi == 'Masuk_Kembali') {
                snWrapper.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div> Loading...';

                // Panggil API dengan status spesifik
                fetch(`/gudang/get-sn/${barangId}/${apiStatus}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let options = '';
                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.serial_number}</option>`;
                            });

                            snWrapper.innerHTML = `
                            <select name="serial_numbers[${thisRowIndex}][]" class="form-control select-sn" multiple required style="height: 100px">
                                ${options}
                            </select>
                            <small class="text-primary font-italic" style="font-size: 0.8rem">
                                *Pilih SN yang ${jenisTransaksi == 'Keluar' ? 'akan dibawa' : 'dikembalikan'}.
                            </small>
                        `;

                            let selectSN = snWrapper.querySelector('select');
                            qtyInput.readOnly = true;
                            qtyInput.value = 0;

                            selectSN.addEventListener('change', function() {
                                let count = Array.from(this.selectedOptions).length;
                                qtyInput.value = count;
                            });
                        } else {
                            // Jika kosong (misal tidak ada barang yg sedang dipinjam)
                            let msg = (jenisTransaksi == 'Masuk_Kembali') ?
                                'Tidak ada unit yang sedang diluar (Terpakai).' :
                                'Stok Habis / Barang Non-SN.';
                            snWrapper.innerHTML =
                                `<input type="text" class="form-control bg-warning" value="${msg}" disabled>`;

                            // Khusus Non-SN, buka input jumlah
                            if (data.length === 0 && (msg.includes('Non-SN'))) {
                                qtyInput.readOnly = false;
                                qtyInput.value = 1;
                            }
                        }
                    });
            }

            // LOGIKA 2: Masuk Barang Baru (Manual)
            else {
                snWrapper.innerHTML = `
                <textarea name="serial_numbers[${thisRowIndex}]" class="form-control border-success"
                placeholder="Scan/Ketik SN Baru..." rows="2"></textarea>
            `;
                qtyInput.readOnly = false;
                qtyInput.value = 1;

                let textArea = snWrapper.querySelector('textarea');
                textArea.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        qtyInput.value = this.value.split(',').length;
                    }
                });
            }
        }

        function resetTable() {
            let tableBody = document.querySelector('#tabel-keranjang tbody');
            // Hapus semua baris kecuali yang pertama
            while (tableBody.rows.length > 1) {
                tableBody.deleteRow(1);
            }
            // Reset baris pertama
            let firstRow = tableBody.rows[0];
            firstRow.querySelector('select.select-barang').value = "";
            firstRow.querySelector('.sn-wrapper').innerHTML =
                '<input type="text" class="form-control bg-light" placeholder="Pilih barang dahulu..." disabled>';
            firstRow.querySelector('.qty-input').value = 1;
            firstRow.querySelector('.qty-input').readOnly = false;
        }
    </script>
@endpush
