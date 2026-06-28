<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
    protected $fillable = ['production_id', 'egg_category_id', 'ikat', 'papan', 'sisa_butir'];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }
}
