<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $table = 'kandang';
    protected $fillable = ['kode', 'nama', 'lokasi', 'kapasitas_ayam', 'status'];

    public function productions()
    {
        return $this->hasMany(Production::class, 'kandang_id');
    }
}
