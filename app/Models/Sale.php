<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['tanggal', 'nomor_invoice', 'customer', 'catatan', 'created_by'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }
}
