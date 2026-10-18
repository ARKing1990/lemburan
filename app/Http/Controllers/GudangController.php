<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    // 1. HALAMAN DAFTAR BARANG (STOK SAAT INI)
    public function index()
    {
        // Ambil semua barang
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('gudang.index', compact('barangs'));
    }

    // 2. HALAMAN FORM TRANSAKSI (MASUK / KELUAR)
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('gudang.create', compact('barangs'));
    }
    public function getSnByBarang($id, $status = 'Tersedia')
    {
        // Default 'Tersedia'. Jika minta 'Terpakai', dia akan kasih list barang yg sedang diluar
        $details = \App\Models\DetailBarang::where('barang_id', $id)
            ->where('status', $status)
            ->get(['id', 'serial_number']);

        return response()->json($details);
    }

    // 3. PROSES SIMPAN TRANSAKSI (LOGIKA UTAMA)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required', // Tidak pakai in:Masuk,Keluar lagi karena ada opsi baru
            'keterangan_event' => 'required|string',
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->barang_id as $key => $idBarang) {

                    $jumlah = $request->jumlah[$key];
                    $barang = Barang::findOrFail($idBarang);
                    $jenis = $request->jenis; // Values: 'Keluar', 'Masuk_Baru', 'Masuk_Kembali'
                    $inputSN = $request->input('serial_numbers.' . $key);
                    $kondisi = $request->kondisi[$key] ?? 'Baik';

                    // --- 1. BARANG KELUAR (AMBIL DARI GUDANG) ---
                    if ($jenis == 'Keluar') {
                        if (!empty($inputSN)) {
                            // Input berupa Array ID dari Dropdown
                            $detailIds = is_array($inputSN) ? $inputSN : [$inputSN];

                            foreach ($detailIds as $detailId) {
                                $detail = \App\Models\DetailBarang::findOrFail($detailId);
                                $detail->update(['status' => 'Terpakai']);

                                TransaksiBarang::create([
                                    'user_id' => Auth::id(),
                                    'barang_id' => $idBarang,
                                    'detail_barang_id' => $detail->id,
                                    'jenis' => 'Keluar',
                                    'jumlah' => 1,
                                    'kondisi' => $kondisi,
                                    'keterangan' => $request->keterangan_event . " (SN: {$detail->serial_number})",
                                    'tanggal_transaksi' => $request->tanggal,
                                ]);
                            }
                            $barang->decrement('stok', count($detailIds));
                        } else {
                            // Non-SN
                            if ($barang->stok < $jumlah) throw new \Exception("Stok {$barang->nama_barang} kurang!");
                            $barang->decrement('stok', $jumlah);
                            $this->catatLog($idBarang, 'Keluar', $jumlah, $kondisi, $request);
                        }
                    }

                    // --- 2. BARANG MASUK - KEMBALI DARI EVENT (PILIH DARI LIST TERPAKAI) ---
                    elseif ($jenis == 'Masuk_Kembali') {
                        if (!empty($inputSN)) {
                            // Input berupa Array ID (Barang yg statusnya Terpakai)
                            $detailIds = is_array($inputSN) ? $inputSN : [$inputSN];

                            foreach ($detailIds as $detailId) {
                                $detail = \App\Models\DetailBarang::findOrFail($detailId);
                                $detail->update(['status' => 'Tersedia']); // Balikin jadi Tersedia

                                TransaksiBarang::create([
                                    'user_id' => Auth::id(),
                                    'barang_id' => $idBarang,
                                    'detail_barang_id' => $detail->id,
                                    'jenis' => 'Masuk',
                                    'jumlah' => 1,
                                    'kondisi' => $kondisi,
                                    'keterangan' => $request->keterangan_event . " (Kembali: {$detail->serial_number})",
                                    'tanggal_transaksi' => $request->tanggal,
                                ]);
                            }
                            $barang->increment('stok', count($detailIds));
                        } else {
                            // Non-SN (Sama saja logicnya dengan Masuk Baru)
                            $barang->increment('stok', $jumlah);
                            $this->catatLog($idBarang, 'Masuk', $jumlah, $kondisi, $request);
                        }
                    }

                    // --- 3. BARANG MASUK - BARU (KETIK MANUAL) ---
                    elseif ($jenis == 'Masuk_Baru') {
                        if (!empty($inputSN)) {
                            // Input berupa String Koma (Manual Ketik)
                            $snList = explode(',', $inputSN);
                            $count = 0;
                            foreach ($snList as $sn) {
                                $sn = trim($sn);
                                if ($sn == "") continue;

                                // Cek duplikat
                                $exists = \App\Models\DetailBarang::where('serial_number', $sn)->exists();
                                if ($exists) throw new \Exception("SN $sn sudah ada di database! Gunakan opsi 'Barang Kembali' jika ini barang lama.");

                                $newDetail = \App\Models\DetailBarang::create([
                                    'barang_id' => $idBarang,
                                    'serial_number' => $sn,
                                    'status' => 'Tersedia'
                                ]);

                                TransaksiBarang::create([
                                    'user_id' => Auth::id(),
                                    'barang_id' => $idBarang,
                                    'detail_barang_id' => $newDetail->id,
                                    'jenis' => 'Masuk',
                                    'jumlah' => 1,
                                    'kondisi' => 'Baik',
                                    'keterangan' => $request->keterangan_event . " (Barang Baru)",
                                    'tanggal_transaksi' => $request->tanggal,
                                ]);
                                $count++;
                            }
                            $barang->increment('stok', $count);
                        } else {
                            // Non-SN
                            $barang->increment('stok', $jumlah);
                            $this->catatLog($idBarang, 'Masuk', $jumlah, $kondisi, $request);
                        }
                    }
                }
            });
            return redirect()->route('gudang.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // Helper kecil agar kodingan tidak kepanjangan
    private function catatLog($barangId, $jenis, $jumlah, $kondisi, $request)
    {
        \App\Models\TransaksiBarang::create([
            'user_id' => Auth::id(),
            'barang_id' => $barangId,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'kondisi' => $kondisi,
            'keterangan' => $request->keterangan_event,
            'tanggal_transaksi' => $request->tanggal,
        ]);
    }

    // 4. DETAIL RIWAYAT PER BARANG (KARTU STOK)
    public function show($id)
    {
        // Tambahkan 'transaksi.detail_barang' agar data SN terpanggil
        $barang = Barang::with(['transaksi' => function ($q) {
            $q->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc');
        }, 'transaksi.user', 'transaksi.detail_barang']) // <--- TAMBAHKAN INI
            ->findOrFail($id);

        return view('gudang.show', compact('barang'));
    }
    // --- TAMBAHAN UNTUK MASTER DATA BARANG ---

    // 1. Form Tambah Barang Baru
    public function createBarang()
    {
        return view('gudang.barang.create');
    }

    // 2. Proses Simpan Barang Baru
    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string',
            'stok'        => 'required|integer|min:0',
        ]);

        // PERBAIKAN: Jangan pakai $request->all(), tapi definisikan satu per satu
        // agar 'kode_barang' tidak ikut tersimpan.
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'satuan'      => $request->satuan,
            'stok'        => $request->stok
        ]);

        return redirect()->route('gudang.index')->with('success', 'Barang baru berhasil ditambahkan!');
    }

    // 3. Form Edit Barang
    public function editBarang($id)
    {
        // Ambil barang beserta detail SN-nya
        $barang = Barang::with('details')->findOrFail($id);
        return view('gudang.barang.edit', compact('barang'));
    }
    public function updateSn(Request $request, $detailId)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:detail_barangs,serial_number,' . $detailId
        ]);

        $detail = \App\Models\DetailBarang::findOrFail($detailId);
        $oldSn = $detail->serial_number;

        $detail->update([
            'serial_number' => $request->serial_number
        ]);

        return back()->with('success', "SN berhasil diubah dari $oldSn menjadi {$request->serial_number}");
    }

    // 3. FUNGSI BARU: HAPUS SN SPESIFIK (Opsional, untuk koreksi stok hantu)
    public function deleteSn($detailId)
    {
        $detail = \App\Models\DetailBarang::findOrFail($detailId);
        $barang = Barang::findOrFail($detail->barang_id);

        // Hapus dan kurangi stok master
        $detail->delete();
        $barang->decrement('stok');

        return back()->with('success', 'Unit SN berhasil dihapus dan stok dikurangi.');
    }

    // 4. Proses Update Barang
    public function updateBarang(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string',
        ]);

        // PERBAIKAN: Pastikan hanya update kolom yang ada
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan'      => $request->satuan,
            // Stok tidak diupdate disini (lewat transaksi)
        ]);

        return redirect()->route('gudang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    // 5. Hapus Barang
    public function destroyBarang($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('gudang.index')->with('success', 'Barang berhasil dihapus dari database!');
    }
    public function laporan(Request $request)
    {
        // 1. Ambil Filter Tanggal
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-d');

        // 2. Ambil Data Transaksi
        $data = TransaksiBarang::with(['barang', 'user', 'detail_barang'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. GROUPING LOGIC (PERBAIKAN)
        // Kita "bersihkan" keterangan agar SN tidak dianggap beda event
        $groupedData = $data->groupBy(function ($item) {

            // Logic: Ambil teks sebelum tanda kurung buka "(" pertama
            // Contoh: "Event Netflix (SN: 123)" -> jadi "Event Netflix"
            $cleanName = explode(' (', $item->keterangan)[0];

            // Grouping Key: TANGGAL | NAMA_BERSIH | JENIS | USER
            return $item->tanggal_transaksi . '|' . $cleanName . '|' . $item->jenis . '|' . ($item->user->name ?? 'System');
        });

        return view('gudang.laporan', compact('groupedData', 'startDate', 'endDate'));
    }
}
