<!DOCTYPE html>
<html lang="id">

<head>
    <title>Input Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5 bg-light">
    <div class="container bg-white p-4 shadow rounded" style="max-width: 600px;">
        <h3 class="mb-4">Input Data Lembur</h3>

        <form action="{{ route('lembur.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" step="3600" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" step="3600" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Keterangan / Aktivitas</label>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="" disabled selected>Pilih Kegiatan...</option>
                            <option value="Instalasi">Instalasi</option>
                            <option value="Standby">Standby</option>
                            <option value="Dismantle">Dismantle</option>
                            <option value="Standby & Dismantle">Standby & Dismantle</option>
                            <option value="Instalasi & Standby">Instalasi & Standby</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Event / Keterangan</label>
                        <input type="text" name="nama_event" class="form-control" placeholder="Contoh: Event Samsung"
                            required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label>Ditugaskan Oleh</label>
                <input type="text" name="ditugaskan_oleh" class="form-control" value="Bu Nina">
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
            <a href="{{ route('lembur.index') }}" class="btn btn-link w-100 mt-2">Kembali</a>
        </form>
    </div>
</body>

</html>
