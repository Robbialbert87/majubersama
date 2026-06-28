<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    public $timestamps = false;
    protected $fillable = ['egg_category_id', 'ikat', 'papan', 'sisa_butir', 'updated_at'];

    public function eggCategory()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }
}
