<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $table = 'daily_prices';
    protected $fillable = ['tanggal', 'egg_size_id', 'harga_per_butir', 'created_by'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class, 'egg_size_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
