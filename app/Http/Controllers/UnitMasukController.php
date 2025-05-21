<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Keterangan;
use App\Models\StatusMobil;
use App\Constants\Constants;
use App\Models\KondisiMobil;
use App\Models\KeteranganMobil;
use Illuminate\Support\Facades\DB;

class UnitMasukController extends Controller
{
    protected $modelKeteranganMobil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->modelKeteranganMobil = new KeteranganMobil();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mengambil semua data mobil beserta status terbaru (jika ada)
        $mobil = Mobil::with('latestStatusMobil', 'latestKeteranganMobil')
                        ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', '!=', Constants::KETERANGAN_MOBIL_PARKIR)
                        ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', '!=', Constants::KETERANGAN_MOBIL_SUDAH_DIPERBAIKI)
                        ->get();
        $kondisiMobil = KondisiMobil::all();

        // dd($mobil->first()->latestKeteranganMobil->keterangan->keterangan);
        return view('unitmasuk', compact('mobil', 'kondisiMobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_rangka' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'kapal_pembawa' => 'nullable|string|max:255',
            'keterangan_status' => 'nullable|string',
            'kode_parkir' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Simpan data mobil
            $mobil = Mobil::create([
                'nomor_rangka' => $request->nomor_rangka,
                'model' => $request->model,
                'warna' => $request->warna,
                'tanggal_masuk' => $request->tanggal_masuk,
                'kapal_pembawa' => $request->kapal_pembawa,
            ]);

            // Simpan data status mobil jika keterangan_status diisi
            if ($request->filled('kode_parkir')) {
                $statusMobil = StatusMobil::create([
                    'id_mobil' => $mobil->id_mobil,
                ]);

            }

            // Tambahkan ke kondisi_mobil jika status Defect
            if ($request->keterangan_status === Constants::KETERANGAN_MOBIL_DEFECT) {
                KondisiMobil::create([
                    'id_mobil' => $mobil->id_mobil,
                ]);
            }

            $this->modelKeteranganMobil->createKeteranganMobil($mobil->id_mobil, $request->keterangan_status);

            DB::commit();
            return redirect()->route('unitmasuk')->with('success', 'Data mobil berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_rangka' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'kapal_pembawa' => 'nullable|string|max:255',
            'keterangan_status' => 'required|string|in:Dicuci,Dikeringkan,Parkir,Defect',
            'kode_parkir' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update data mobil
            $mobil = Mobil::findOrFail($id);
            $mobil->update([
                'nomor_rangka' => $request->nomor_rangka,
                'model' => $request->model,
                'warna' => $request->warna,
                'tanggal_masuk' => $request->tanggal_masuk,
                'kapal_pembawa' => $request->kapal_pembawa,
            ]);

            // Simpan data status mobil
            $statusData = [
                'id_mobil' => $mobil->id_mobil,
                'kode_parkir' => $request->kode_parkir,
            ];

            if ($request->keterangan_status === Constants::KETERANGAN_MOBIL_PARKIR && $request->kode_parkir) {
                $statusData['kode_parkir'] = $request->kode_parkir;
            }
            
            StatusMobil::create($statusData);
            
            $this->modelKeteranganMobil->createKeteranganMobil($mobil->id_mobil, $request->keterangan_status);

            // Tambahkan ke kondisi_mobil jika status Defect dan belum ada entri
            if ($request->keterangan_status === Constants::KETERANGAN_MOBIL_DEFECT) {
                $sudahAda = KondisiMobil::where('id_mobil', $mobil->id_mobil)->exists();
                if (!$sudahAda) {
                    KondisiMobil::create([
                        'id_mobil' => $mobil->id_mobil,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('unitmasuk')->with('success', 'Data mobil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $statusMobil = StatusMobil::where('id_mobil', $mobil->id_mobil)->first();
        $kondisiMobil = KondisiMobil::where('id_mobil', $mobil->id_mobil)->first();
        $keteranganMobil = KeteranganMobil::where('id_mobil', $mobil->id_mobil)->first();

        $data = [
            'status_mobil' => $statusMobil,
            'kondisi_mobil' => $kondisiMobil,
            'keterangan_mobil' => $keteranganMobil,
            'mobil' => $mobil,
        ];

        // looping hapus data jika datanya tidak null, jika null di skip
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $value->delete();
            }
        }

        return redirect()->route('unitmasuk')->with('success', 'Data mobil berhasil dihapus!');
    }
}
