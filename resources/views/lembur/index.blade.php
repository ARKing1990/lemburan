@extends('layouts.adminlte')

@section('title', 'Lemburan Saya')
@section('header_title', 'Data Lembur Saya')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h5>Halo, {{ Auth::user()->name }}!</h5>
                <p>Ini adalah halaman dashboard pribadi Anda. Anda dapat mengelola data lembur di sini.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $bulanDipilih)->translatedFormat('F Y') }}</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('lembur.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Lembur
                    </a>
                    <form action="{{ route('lembur.index') }}" method="GET" class="d-flex align-items-center ml-2">
                        <input type="month" name="bulan" class="form-control" value="{{ $bulanDipilih }}" onchange="this.form.submit()">
                    </form>
                </div>
                <a href="{{ route('lembur.pdf', ['bulan' => $bulanDipilih]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>

            <table id="tabel-lembur" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Durasi</th>
                        <th>Keterangan</th>
                        <th>Ditugaskan Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataLembur as $lembur)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($lembur->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $lembur->jam_mulai }}</td>
                        <td>{{ $lembur->jam_selesai }}</td>
                        <td>{{ $lembur->jumlah_jam }} Jam</td>
                        <td>{{ $lembur->keterangan }}</td>
                        <td>{{ $lembur->ditugaskan_oleh }}</td>
                        <td>
                            <a href="{{ route('lembur.edit', $lembur->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('lembur.destroy', $lembur->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="3" class="text-right">Total Jam:</td>
                        <td>{{ $totalJam }} Jam</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
  $(function () {
    $("#tabel-lembur").DataTable({
      "responsive": true, "autoWidth": false, "ordering": false
    });
  });
</script>
@endpush
