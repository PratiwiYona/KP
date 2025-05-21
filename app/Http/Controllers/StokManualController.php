<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use App\Models\Mobil;
use App\Models\Keterangan;
use App\Models\StatusMobil;

class StokManualController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mobil dengan keterangan "Parkir"
        $mobilParkir = Mobil::with('latestStatusMobil', 'latestKeteranganMobil', 'latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', 'Parkir')
            ->get();

        // Mobil dengan keterangan "Defect"
        $mobilDefect = Mobil::with('latestStatusMobil', 'latestKeteranganMobil', 'latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', 'Defect')
            ->get();

        // Mobil dengan keterangan "Sudah Diperbaiki"
        $mobilSudahDiperbaiki = Mobil::with('latestStatusMobil', 'latestKeteranganMobil', 'latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', 'Sudah Diperbaiki')
            ->get();
        
        // Mobil dengan keterangan "Maintenance"
        $mobilMaintenance = Mobil::with('latestStatusMobil', 'latestKeteranganMobil', 'latestKondisiMobil')
            ->whereRelation('latestKeteranganMobil.keterangan', 'keterangan', 'Maintenance')
            ->get();

        // Gabungkan semua hasil
        $mobil = $mobilParkir->merge($mobilDefect)->merge($mobilSudahDiperbaiki)->merge($mobilMaintenance);

        return view('stokmanual', compact('mobil'));
    }

    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();

        return redirect()->route('stokmanual')->with('success', 'Data berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'kode_parkir' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            // Cari mobil berdasarkan ID
            $mobil = Mobil::findOrFail($id);
            
            // Cari ID keterangan berdasarkan nama keterangan
            $keterangan = Keterangan::where('keterangan', $request->keterangan)->first();
            if (!$keterangan) {
                throw new \Exception('Keterangan tidak ditemukan.');
            }

            // Perbarui data di tabel keterangan_mobil
            if ($mobil->latestKeteranganMobil) {
                $mobil->latestKeteranganMobil->update([
                    'id_keterangan' => $keterangan->id,
                ]);
            }

            // Perbarui data di tabel status_mobil
            if ($mobil->latestStatusMobil) {
                $mobil->latestStatusMobil->update([
                    'kode_parkir' => $request->kode_parkir,
                ]);
            } else {
                // Jika tidak ada entri di status_mobil, buat entri baru
                StatusMobil::create([
                    'id_mobil' => $mobil->id_mobil,
                    'kode_parkir' => $request->kode_parkir,
                ]);
            }


            DB::commit();
            return redirect()->route('stokmanual')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
