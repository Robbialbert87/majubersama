<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->integer('pecah')->default(0)->after('catatan');
        });

        DB::transaction(function () {
            $pecah = DB::table('egg_categories')->where('kode', 'R')->first();
            if ($pecah) {
                DB::table('production_items')->where('egg_category_id', $pecah->id)->delete();
                DB::table('stocks')->where('egg_category_id', $pecah->id)->delete();
                DB::table('egg_categories')->where('id', $pecah->id)->delete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('pecah');
        });
    }
};
