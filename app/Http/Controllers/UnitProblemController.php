<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Mobil;
use App\Models\StatusMobil;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Models\KondisiMobil;
use App\Models\KeteranganMobil;
use App\Models\Keterangan;

class UnitProblemController extends Controller
{
    protected $modelKeteranganMobil;

    public function __construct()
    {
        $this->middleware('auth');
        $this->modelKeteranganMobil = new KeteranganMobil();
    }

    public function index()
    {
        $mobilSudahDiperbaiki = Mobil::with('latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', Constants::KETERANGAN_MOBIL_SUDAH_DIPERBAIKI)
            ->get();

        $mobilMaintenance = Mobil::with('latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', Constants::KETERANGAN_MOBIL_MAINTENANCE)
            ->get();

        $mobilDefect = Mobil::with('latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', Constants::KETERANGAN_MOBIL_DEFECT)
            ->get();

        $unitProblems = $mobilSudahDiperbaiki->merge($mobilMaintenance)->merge($mobilDefect);

        return view('unitproblem', compact('unitProblems'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'catatan_defect' => 'nullable|string',
                'tanggal_masuk_bengkel' => 'nullable|date',
                'tanggal_keluar_bengkel' => 'nullable|date',
                'klaim_warranty' => 'nullable|string',
                'update_kondisi_unit' => 'nullable|string',
            ]);

            // Simpan data kondisi mobil
            $kondisiMobil = KondisiMobil::create([
                'id_mobil' => $request->id_mobil,
                'catatan_defect' => $request->catatan_defect,
                'tanggal_masuk_bengkel' => $request->tanggal_masuk_bengkel,
                'tanggal_keluar_bengkel' => $request->tanggal_keluar_bengkel,
                'klaim_warranty' => $request->klaim_warranty,
            ]);

            // Simpan keterangan mobil
            $this->modelKeteranganMobil->createKeteranganMobil($request->id_mobil, $request->update_kondisi_unit);

            DB::commit();
            return redirect()->route('unitproblem')->with('success', 'Data kondisi mobil berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data kondisi mobil: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_kondisi)
{
    $request->validate([
        'catatan_defect' => 'nullable|string',
        'tanggal_masuk_bengkel' => 'nullable|date',
        'tanggal_keluar_bengkel' => 'nullable|date',
        'klaim_warranty' => 'nullable|string',
        'update_kondisi_unit' => 'nullable|string',
    ]);
    
    try {
        DB::beginTransaction();
        
        // Cari data kondisi mobil berdasarkan id_kondisi
        $kondisiMobil = KondisiMobil::findOrFail($id_kondisi);
        // dd($kondisiMobil->id_mobil, $request->update_kondisi_unit);

        // Perbarui data di tabel kondisi_mobil
        $kondisiMobil->update([
            'catatan_defect' => $request->catatan_defect,
            'tanggal_masuk_bengkel' => $request->tanggal_masuk_bengkel,
            'tanggal_keluar_bengkel' => $request->tanggal_keluar_bengkel,
            'klaim_warranty' => $request->klaim_warranty,
        ]);

        // Perbarui keterangan_mobil jika request keterangan tidak kosong
        if (!empty($request->update_kondisi_unit)) {
            // Simpan keterangan mobil
            $this->modelKeteranganMobil->createKeteranganMobil($kondisiMobil->id_mobil, $request->update_kondisi_unit);
        }

        DB::commit();
        return redirect()->route('unitproblem')->with('success', 'Data kondisi mobil berhasil diperbarui!');
    } catch (\Throwable $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal memperbarui data kondisi mobil: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        $kondisiMobil = KondisiMobil::findOrFail($id);
        $kondisiMobil->delete();

        return redirect()->route('unitproblem')->with('success', 'Data kondisi mobil berhasil dihapus!');
    }
}
