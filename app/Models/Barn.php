<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barn extends Model
{
    protected $table = 'barns';
    protected $fillable = ['kode', 'nama', 'lokasi', 'kapasitas_ayam', 'status'];

    public function productions()
    {
        return $this->hasMany(Production::class, 'barn_id');
    }
}
