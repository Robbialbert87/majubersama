<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $fillable = ['tanggal_berlaku', 'jumbo', 'besar', 'sedang', 'kecil', 'putih', 'created_by'];

    protected function casts(): array
    {
        return ['tanggal_berlaku' => 'date'];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
