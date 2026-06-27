<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    protected $table = 'production_details';
    protected $fillable = [
        'production_id', 'egg_size_id', 'jumlah_butir',
        'jumlah_papan', 'jumlah_ikat', 'harga_per_butir', 'subtotal', 'sisa_butir'
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class, 'egg_size_id');
    }
}
