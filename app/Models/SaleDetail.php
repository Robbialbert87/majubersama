<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = ['sale_id', 'egg_category_id', 'ikat', 'papan', 'harga_per_butir', 'subtotal'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }
}
