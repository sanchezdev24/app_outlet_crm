<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->json('images')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->timestamp('discount_valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'brand_id', 'is_active']);
            $table->index('sku');
            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};