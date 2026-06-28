<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = ['tanggal', 'barn_id', 'catatan', 'created_by'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function barn()
    {
        return $this->belongsTo(Barn::class, 'barn_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ProductionItem::class, 'production_id');
    }
}
