<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'productions';
    protected $fillable = [
        'tanggal', 'kandang_id', 'ayam_besar', 'ayam_kecil',
        'total_produksi', 'telur_putih', 'telur_pecah', 'catatan', 'created_by'
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'kandang_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(ProductionDetail::class, 'production_id');
    }
}
