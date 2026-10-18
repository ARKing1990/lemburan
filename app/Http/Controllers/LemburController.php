<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Panggil library PDF
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $bulanDipilih = $request->input('bulan', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $bulanDipilih);
        $startDate = $date->copy()->subMonth()->day(26)->startOfDay();
        $endDate = $date->copy()->day(25)->endOfDay();

        // FILTER: Tambahkan where('user_id', Auth::id())
        $dataLembur = Lembur::where('user_id', Auth::id()) // <--- KUNCI ISOLASI DATA
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalJam = $dataLembur->sum('jumlah_jam');

        return view('lembur.index', compact('dataLembur', 'totalJam', 'bulanDipilih'));
    }

    public function create()
    {
        return view('lembur.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'jenis_kegiatan' => 'required', // Input baru
            'nama_event' => 'required',     // Input baru
        ]);

        // 1. Parsing waktu
        $start = Carbon::parse($request->jam_mulai)->startOfHour();
        $end = Carbon::parse($request->jam_selesai)->startOfHour();

        // 2. Cek apakah lembur melewati tengah malam
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        // 3. HITUNG DURASI (Perbaikan disini)
        // Gunakan start->diffInHours(end) agar urutannya "Dari Mulai KE Selesai"
        $durasi = $start->diffInHours($end);

        $keteranganLengkap = $request->jenis_kegiatan . ' ' . $request->nama_event;

        // 3. Simpan ke Database
        Lembur::create([
            'user_id' => Auth::id(), // <--- OTOMATIS MENGAMBIL ID USER YANG LOGIN
            'nama_pegawai' => Auth::user()->name, // Mengambil nama dari akun User
            'tanggal' => $request->tanggal,
            'jam_mulai' => $start->format('H:i'), // Simpan jam yang sudah bersih
            'jam_selesai' => $end->format('H:i'),
            'jumlah_jam' => $durasi,
            'keterangan' => $keteranganLengkap, // Masukkan hasil gabungan tadi
            'ditugaskan_oleh' => $request->ditugaskan_oleh ?? 'Bu Nina',
        ]);

        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil disimpan!');
    }

    // 1. Menampilkan Form Edit dengan data lama
    public function edit($id)
    {
        $lembur = Lembur::findOrFail($id);
        return view('lembur.edit', compact('lembur'));
    }

    // 2. Proses Menyimpan Perubahan (Update)
    public function update(Request $request, $id)
    {
        // 1. UPDATE VALIDASI (Bagian ini yang menyebabkan error sebelumnya)
        // Hapus 'keterangan', ganti dengan input baru Anda
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'jenis_kegiatan' => 'required', // Tambahkan ini
            'nama_event' => 'required',     // Tambahkan ini
        ]);

        // 2. Parsing waktu & Reset Menit
        $start = Carbon::parse($request->jam_mulai)->startOfHour();
        $end = Carbon::parse($request->jam_selesai)->startOfHour();

        // 3. Cek apakah lembur melewati tengah malam
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        // 4. Hitung Durasi
        $durasi = $start->diffInHours($end);

        // 5. GABUNGKAN STRING (Satukan Jenis Kegiatan + Nama Event)
        $keteranganLengkap = $request->jenis_kegiatan . ' ' . $request->nama_event;

        // 6. Update Database
        $lembur = Lembur::findOrFail($id);
        $lembur->update([
            'tanggal' => $request->tanggal,
            'jam_mulai' => $start->format('H:i'),
            'jam_selesai' => $end->format('H:i'),
            'jumlah_jam' => $durasi,
            'keterangan' => $keteranganLengkap, // Data hasil gabungan masuk sini
            'ditugaskan_oleh' => $request->ditugaskan_oleh,
        ]);

        return redirect()->route('lembur.index')->with('success', 'Data berhasil diperbarui!');
    }

    // 3. Proses Hapus Data
    public function destroy($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->delete();

        return redirect()->route('lembur.index')->with('success', 'Data berhasil dihapus!');
    }

    public function downloadPdf(Request $request)
    {
        // 1. Ambil User yang sedang Login
        $user = Auth::user();

        // 2. Logika Tanggal & Cutoff (Tetap sama)
        $bulanDipilih = $request->input('bulan', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $bulanDipilih);
        $startDate = $date->copy()->subMonth()->day(26)->startOfDay();
        $endDate = $date->copy()->day(25)->endOfDay();

        // 3. Ambil Data (Tetap sama)
        $dataLembur = Lembur::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalJam = $dataLembur->sum('jumlah_jam');
        $periodeLabel = $date->locale('id')->translatedFormat('F Y');

        // 4. KIRIM VARIABEL $user KE VIEW
        $pdf = Pdf::loadView('lembur.pdf', compact('dataLembur', 'totalJam', 'periodeLabel', 'user'));

        // 5. NAMA FILE DINAMIS (Contoh: Laporan-Lembur-Firzha-Ramadhan-Februari-2026.pdf)
        $namaFileClean = str_replace(' ', '-', $user->name); // Ganti spasi dengan strip
        $namaFile = 'Laporan-Lembur-' . $namaFileClean . '-' . $date->locale('id')->translatedFormat('F-Y') . '.pdf';

        return $pdf->download($namaFile);
    }
}
