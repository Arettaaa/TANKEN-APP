<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique(); // misal: PAY-001
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->unsignedBigInteger('amount');
            $table->string('method')->nullable(); // credit_card, bank_transfer, qris
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable(); // ID Resi dari Payment Gateway
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};