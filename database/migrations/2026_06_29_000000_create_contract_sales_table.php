<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_sales', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('barn_id')->constrained('barns');
            $table->foreignId('egg_category_id')->constrained('egg_categories');
            $table->foreignId('production_item_id')->constrained('production_items')->onDelete('cascade');
            $table->integer('jumlah_ikat')->default(0);
            $table->integer('harga_per_butir')->default(0);
            $table->integer('total_butir')->default(0);
            $table->integer('total_penjualan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_sales');
    }
};
