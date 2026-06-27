<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyProduction extends Model
{
    protected $table = 'daily_productions';

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    protected $fillable = [
        'tanggal', 'egg_category_id', 'butir_hari_ini', 'butir_pecah', 'butir_rijek',
        'stok_sisa_sebelumnya', 'total_butir', 'jumlah_papan', 'sisa_butir',
        'harga_per_butir', 'nilai_produksi', 'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(EggCategory::class, 'egg_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
