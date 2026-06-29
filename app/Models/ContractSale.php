<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractSale extends Model
{
    protected $fillable = [
        'tanggal', 'barn_id', 'egg_category_id', 'production_item_id',
        'jumlah_ikat', 'harga_per_butir', 'total_butir', 'total_penjualan',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function barn()
    {
        return $this->belongsTo(Barn::class, 'barn_id');
    }

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }

    public function productionItem()
    {
        return $this->belongsTo(ProductionItem::class, 'production_item_id');
    }
}
