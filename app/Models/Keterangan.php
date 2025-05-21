<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keterangan extends Model
{
    protected $table = 'keterangan';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function statusMobil()
    {
        return $this->hasMany(StatusMobil::class, 'id_keterangan', 'id');
    }
    public function keteranganMobil()
    {
        return $this->hasMany(KeteranganMobil::class, 'id_keterangan', 'id');
    }
}
