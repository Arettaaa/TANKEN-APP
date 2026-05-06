<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete(); // kalau user dihapus, cart-nya ikut hapus

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete(); // kalau produk dihapus, cart-nya ikut hapus

            $table->string('color')->nullable(); // warna bisa null kalau produk ga punya pilihan warna
            $table->string('size');              // ukuran wajib ada
            $table->unsignedSmallInteger('quantity')->default(1); // min 1, max 99

            $table->timestamps();

            $table->unique(['user_id', 'product_id', 'color', 'size'], 'cart_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};