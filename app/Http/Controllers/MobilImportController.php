<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\StatusMobil;
use App\Models\KondisiMobil;
use App\Models\KeteranganMobil;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MobilImportController extends Controller
{
    public function showForm()
    {
        return view('import');
    }

    public function importUnitMasuk(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            DB::beginTransaction();
            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // dd($rows);

            // Lewati baris pertama (header)
            foreach (array_slice($rows, 1) as $row) {
                Mobil::create([
                    'nomor_rangka' => $row[1],
                    'model' => $row[2],
                    'warna'     => $row[3],
                    'tanggal_masuk'     => $row[4],
                    'kapal_pembawa'    => $row[5],
                ]);

                $mobil = Mobil::where('nomor_rangka', $row[1])->first();

                // Tambahkan ke kondisi_mobil jika status Defect
                KondisiMobil::create([
                    'id_mobil' => $mobil->id_mobil,
                ]);

                $modelKeteranganMobil = new KeteranganMobil();
                $modelKeteranganMobil->createKeteranganMobil($mobil->id_mobil, Constants::KETERANGAN_MOBIL_DICUCI);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data mobil berhasil diimport!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport data mobil: ' . $e->getMessage());
        }
    }

    public function importUnitKeluar(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        
        try {
            DB::beginTransaction();
            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $countMobilDihapus = 0;

            // Lewati baris pertama (header)
            foreach (array_slice($rows, 1) as $row) {
                $nomorRangka = $row[1]; // Kolom pertama anggap sebagai nomor rangka
                if ($nomorRangka) {
                    // Cek apakah ada mobil di database dengan nomor rangka yang sama
                    $mobil = Mobil::where('nomor_rangka', $nomorRangka)->first();
                    if ($mobil) {
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

                        $countMobilDihapus++;
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data unit keluar berhasil diproses!');
        }  catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport data unit keluar: ' . $e->getMessage());
        }
    }
}
