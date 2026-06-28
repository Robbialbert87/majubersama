<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggCategory extends Model
{
    protected $fillable = ['kode', 'nama', 'unit_penjualan', 'urutan', 'status'];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'egg_category_id');
    }

    public function productionItems()
    {
        return $this->hasMany(ProductionItem::class, 'egg_category_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'egg_category_id');
    }
}
