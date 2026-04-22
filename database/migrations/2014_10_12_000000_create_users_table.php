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
            
            // TAMBAHAN 1: Kolom google_id (Boleh kosong/nullable kalau daftarnya manual)
            $table->string('google_id')->nullable(); 
            
            $table->timestamp('email_verified_at')->nullable();
            
            // TETAP ADA: Password untuk login manual biasa
            $table->string('password'); 
            
            // TAMBAHAN 2: Diubah jadi enum agar lebih ketat dan aman
            $table->enum('role', ['super_admin', 'admin_gudang', 'customer'])->default('customer'); 
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};