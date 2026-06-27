<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user');
            $table->string('status')->default('Active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

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
            $table->string('jenis'); // in or out
            $table->integer('jumlah_butir')->default(0);
            $table->integer('jumlah_papan')->default(0);
            $table->integer('jumlah_ikat')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('production_details');
        Schema::dropIfExists('productions');
        Schema::dropIfExists('daily_prices');
        Schema::dropIfExists('egg_sizes');
        Schema::dropIfExists('kandang');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
