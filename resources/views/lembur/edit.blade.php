<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Data Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5 bg-light">
    <div class="container bg-white p-4 shadow rounded" style="max-width: 600px;">
        <h3 class="mb-4">Edit Data Lembur</h3>

        <form action="{{ route('lembur.update', $lembur->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $lembur->tanggal }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control"
                        value="{{ \Carbon\Carbon::parse($lembur->jam_mulai)->format('H:i') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control"
                        value="{{ \Carbon\Carbon::parse($lembur->jam_selesai)->format('H:i') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Keterangan / Aktivitas</label>
                <div class="alert alert-info py-2 small">
                    Info: Keterangan saat ini: <strong>{{ $lembur->keterangan }}</strong>. <br>
                    Silakan pilih ulang jenis kegiatan dan nama event di bawah untuk memperbarui.
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="Instalasi">Instalasi</option>
                            <option value="Standby">Standby</option>
                            <option value="Dismantle">Dismantle</option>
                            <option value="Standby & Dismantle">Standby & Dismantle</option>
                            <option value="Instalasi & Standby">Instalasi & Standby</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Event</label>
                        <input type="text" name="nama_event" class="form-control"
                            placeholder="Masukkan Nama Event-nya saja" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label>Ditugaskan Oleh</label>
                <input type="text" name="ditugaskan_oleh" class="form-control"
                    value="{{ $lembur->ditugaskan_oleh }}">
            </div>

            <button type="submit" class="btn btn-warning w-100">Update Data</button>
            <a href="{{ route('lembur.index') }}" class="btn btn-link w-100 mt-2">Batal</a>
        </form>
    </div>
</body>

</html>
