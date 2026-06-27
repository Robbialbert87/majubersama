<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = ['tanggal', 'egg_size_id', 'jenis', 'jumlah_butir', 'jumlah_papan', 'jumlah_ikat', 'keterangan'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class, 'egg_size_id');
    }
}
