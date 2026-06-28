<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Drop old tables
        Schema::dropIfExists('production_details');
        Schema::dropIfExists('stock_mutations');
        Schema::dropIfExists('daily_prices');
        Schema::dropIfExists('productions');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('egg_sizes');
        Schema::dropIfExists('kandang');

        // 1. egg_categories (was egg_sizes)
        Schema::create('egg_categories', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10);
            $table->string('nama', 100);
            $table->enum('unit_penjualan', ['papan', 'ikat', 'tidak'])->default('papan');
            $table->integer('urutan')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 2. barns (was kandang)
        Schema::create('barns', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20);
            $table->string('nama', 100);
            $table->string('lokasi', 200)->nullable();
            $table->integer('kapasitas_ayam')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 3. system_settings
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('butir_per_papan')->default(30);
            $table->integer('papan_per_ikat')->default(5);
            $table->timestamps();
        });

        // 4. daily_prices (restructured — per tanggal, one row per date)
        Schema::create('daily_prices', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_berlaku');
            $table->integer('jumbo')->default(0);
            $table->integer('besar')->default(0);
            $table->integer('sedang')->default(0);
            $table->integer('kecil')->default(0);
            $table->integer('putih')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 5. productions (simplified — just log)
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('barn_id')->constrained('barns');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 6. production_items (replaces production_details — no prices)
        Schema::create('production_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('productions')->onDelete('cascade');
            $table->foreignId('egg_category_id')->constrained('egg_categories');
            $table->integer('ikat')->default(0);
            $table->integer('papan')->default(0);
            $table->integer('sisa_butir')->default(0);
            $table->timestamps();
        });

        // 7. stocks (restructured — ikat, papan, sisa_butir, no price)
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egg_category_id')->constrained('egg_categories')->onDelete('cascade');
            $table->integer('ikat')->default(0);
            $table->integer('papan')->default(0);
            $table->integer('sisa_butir')->default(0);
            $table->timestamp('updated_at')->useCurrent();
        });

        // 8. sales
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor_invoice');
            $table->string('customer')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 9. sale_details
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('egg_category_id')->constrained('egg_categories');
            $table->integer('ikat')->default(0);
            $table->integer('papan')->default(0);
            $table->integer('harga_per_butir')->default(0);
            $table->integer('subtotal')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('sale_details');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('production_items');
        Schema::dropIfExists('productions');
        Schema::dropIfExists('daily_prices');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('barns');
        Schema::dropIfExists('egg_categories');

        // Recreate old tables
        Schema::create('kandang', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('kapasitas_ayam')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::create('egg_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama')->nullable();
            $table->integer('urutan')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::create('daily_prices', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('egg_size_id')->constrained('egg_sizes')->onDelete('cascade');
            $table->integer('harga_per_butir')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kandang_id')->constrained('kandang')->onDelete('cascade');
            $table->integer('ayam_besar')->default(0);
            $table->integer('ayam_kecil')->default(0);
            $table->integer('total_produksi')->default(0);
            $table->integer('telur_putih')->default(0);
            $table->integer('telur_pecah')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('production_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('productions')->onDelete('cascade');
            $table->foreignId('egg_size_id')->constrained('egg_sizes')->onDelete('cascade');
            $table->integer('jumlah_butir')->default(0);
            $table->integer('jumlah_papan')->default(0);
            $table->integer('jumlah_ikat')->default(0);
            $table->integer('harga_per_butir')->default(0);
            $table->integer('subtotal')->default(0);
            $table->integer('sisa_butir')->default(0);
            $table->timestamps();
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egg_size_id')->constrained('egg_sizes')->onDelete('cascade');
            $table->integer('jumlah_butir')->default(0);
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('egg_size_id')->constrained('egg_sizes')->onDelete('cascade');
            $table->string('jenis');
            $table->integer('jumlah_butir')->default(0);
            $table->integer('jumlah_papan')->default(0);
            $table->integer('jumlah_ikat')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }
};
