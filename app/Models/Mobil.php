<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusMobil;
use App\Models\KondisiMobil;

class Mobil extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobil';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_mobil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [''];
    // protected $with = ['latestKeteranganMobil', 'latestStatusMobil'];

    public function keterangans()
    {
        return $this->hasMany(KeteranganMobil::class, 'id_mobil');
    }


    public function statusMobil()
    {
        return $this->hasMany(StatusMobil::class, 'id_mobil', 'id_mobil');
    }
    // Relasi ke kondisi mobil
    public function kondisiMobil()
    {
        return $this->hasOne(KondisiMobil::class, 'id_mobil');
    }

    public function latestKeteranganMobil()
    {
        return $this->hasOne(KeteranganMobil::class, 'id_mobil', 'id_mobil')
            ->latestOfMany('created_at');
    }

    public function latestStatusMobil()
    {
        return $this->hasOne(StatusMobil::class, 'id_mobil', 'id_mobil')
            ->latestOfMany('created_at');
    }

    public function latestKondisiMobil()
    {
        return $this->hasOne(KondisiMobil::class, 'id_mobil', 'id_mobil')
            ->latestOfMany('created_at');
    }
}
