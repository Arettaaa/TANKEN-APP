<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedSmallInteger('unique_code')->default(0)->after('total');
        $table->unsignedBigInteger('total_payment')->default(0)->after('unique_code');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['unique_code', 'total_payment']);
    });
}
};
