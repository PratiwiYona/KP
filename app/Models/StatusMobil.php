<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusMobil extends Model
{
    use HasFactory;
    
    protected $table = 'status_mobil';
    protected $primaryKey = 'id_status';
    
    // Memastikan kode_parkir dan tanggal_status tetap nullable
    protected $guarded = [''];
    
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil', 'id_mobil');
    }
}