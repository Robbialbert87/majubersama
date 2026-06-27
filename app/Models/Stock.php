<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    public $timestamps = false;
    protected $fillable = ['egg_size_id', 'jumlah_butir', 'updated_at'];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class, 'egg_size_id');
    }
}
