<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeteranganMobil;
use App\Models\Keterangan;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
{
    $latestIds = KeteranganMobil::select(DB::raw('MAX(id) as id'))
        ->groupBy('id_mobil')
        ->pluck('id');

    $statusCounts = KeteranganMobil::whereIn('id', $latestIds)
        ->with('keterangan')
        ->get()
        ->groupBy('keterangan.keterangan')
        ->map(function ($group) {
            return $group->count();
        });

    // Pisahkan label dan count untuk chart
    $statusLabels = $statusCounts->keys();
    $statusValues = $statusCounts->values();
    

    return view('dashboard', compact('statusCounts', 'statusLabels', 'statusValues'));
}

}
