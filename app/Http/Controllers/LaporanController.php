<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanItem;
use App\Models\LaporanImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Halaman List Laporan
    public function index()
    {
        $laporans = Laporan::with('user')->orderBy('created_at', 'desc')->get();
        return view('laporan.index', compact('laporans'));
    }

    // Halaman Form Buat Laporan
    public function create()
    {
        return view('laporan.create');
    }

    // Proses Simpan Laporan
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul_laporan' => 'required',
            'nama_event'    => 'required',
            'lokasi'        => 'required',
            'tanggal'       => 'required|date',
            'items'         => 'required|array',
            'items.*.deskripsi' => 'required',
            // Validasi foto: Maksimal 5MB per foto (angka 5120)
            'items.*.fotos.*'   => 'image|mimes:jpeg,png,jpg|max:5120'
        ], [
            // Pesan Error Kustom agar mudah dipahami
            'items.*.deskripsi.required' => 'Deskripsi poin tidak boleh kosong.',
            'items.*.fotos.*.max' => 'Ukuran foto terlalu besar (Maks 5MB per foto).',
            'items.*.fotos.*.image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        try {
            DB::beginTransaction(); // Mulai Transaksi Database

            // 2. Simpan Header Laporan
            $laporan = Laporan::create([
                'user_id'       => Auth::id(), // Pastikan User Login
                'judul_laporan' => $request->judul_laporan,
                'nama_event'    => $request->nama_event,
                'lokasi'        => $request->lokasi,
                'tanggal_laporan' => $request->tanggal,
            ]);

            // 3. Simpan Poin-Poin
            foreach ($request->items as $key => $itemData) {

                // Simpan Deskripsi
                $item = LaporanItem::create([
                    'laporan_id' => $laporan->id,
                    'deskripsi'  => $itemData['deskripsi']
                ]);

                // 4. Simpan Foto (Cek apakah ada file yang diupload di poin ini)
                if ($request->hasFile("items.{$key}.fotos")) {
                    $files = $request->file("items.{$key}.fotos");

                    foreach ($files as $foto) {
                        // Simpan ke folder: storage/app/public/laporan_images
                        $path = $foto->store('laporan_images', 'public');

                        LaporanImage::create([
                            'laporan_item_id' => $item->id,
                            'image_path'      => $path
                        ]);
                    }
                }
            }

            DB::commit(); // Simpan Permanen
            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            // Tampilkan pesan error asli untuk debugging
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // Lihat Detail Laporan
    public function show($id)
    {
        $laporan = Laporan::with(['items.images', 'user'])->findOrFail($id);
        return view('laporan.show', compact('laporan'));
    }
}
