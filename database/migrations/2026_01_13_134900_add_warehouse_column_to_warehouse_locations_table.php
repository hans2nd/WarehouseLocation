<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom warehouse jika belum ada
        if (!Schema::hasColumn('warehouse_locations', 'warehouse')) {
            Schema::table('warehouse_locations', function (Blueprint $table) {
                $table->string('warehouse')->nullable()->after('branch');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_locations', function (Blueprint $table) {
            $table->dropColumn('warehouse');
        });
    }
};
