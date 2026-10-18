@extends('layouts.adminlte')

@section('title', 'Monitoring Lembur')
@section('header_title', 'Monitoring Data Lembur')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Data</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lembur') }}" method="GET" class="row">
                <div class="col-md-4 mb-2">
                    <label>Pilih Pegawai:</label>
                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Tampilkan Semua Pegawai --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Pilih Periode:</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}"
                        onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">
                Data Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
            </h3>
        </div>
        <div class="card-body">
            <table id="tabel-admin-lembur" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pegawai</th>
                        <th>Jam</th>
                        <th>Durasi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataLembur as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>
                                <span class="badge badge-info">{{ $row->user->name ?? 'User Terhapus' }}</span>
                            </td>
                            <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                            <td>{{ $row->jumlah_jam }} Jam</td>
                            <td>{{ $row->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="3" class="text-right">Total Jam (Semua User Tampil):</td>
                        <td colspan="2">{{ $totalJam }} Jam</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#tabel-admin-lembur").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [
                    [0, "desc"]
                ] // Urutkan berdasarkan tanggal (kolom ke-0) descending
            });
        });
    </script>
@endpush
