<?php

namespace App\Models;

use App\Models\Mobil;
use App\Models\Keterangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeteranganMobil extends Model
{
    use HasFactory;

    protected $table = 'keterangan_mobil';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $with = ['keterangan'];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil');
    }

    public function keterangan()
    {
        return $this->belongsTo(Keterangan::class, 'id_keterangan', 'id');
    }

    public function createKeteranganMobil($id_mobil, $keterangan)
    {
        $keterangan = Keterangan::where('keterangan', $keterangan)->first();
        
        return KeteranganMobil::create([
            'id_mobil' => $id_mobil,
            'id_keterangan' => $keterangan->id,
        ]);
    }
}
