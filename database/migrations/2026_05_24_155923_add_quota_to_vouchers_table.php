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
    Schema::table('vouchers', function (Blueprint $table) {
        $table->unsignedInteger('quota')->nullable()->after('usage_limit');
        // quota = total slot tersedia, usage_limit tetap ada untuk kompatibilitas
    });
}

public function down(): void
{
    Schema::table('vouchers', function (Blueprint $table) {
        $table->dropColumn('quota');
    });
}
};
