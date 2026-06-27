<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggSize extends Model
{
    protected $table = 'egg_sizes';
    protected $fillable = ['kode', 'nama', 'urutan', 'status'];

    public function dailyPrices()
    {
        return $this->hasMany(DailyPrice::class, 'egg_size_id');
    }

    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class, 'egg_size_id');
    }
}
