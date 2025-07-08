<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_total', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'transfer'])->default('cash');
            $table->text('notes')->nullable();
            $table->timestamp('sale_date')->useCurrent();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index('sale_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};