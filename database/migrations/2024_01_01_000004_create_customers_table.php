<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->enum('type', ['regular', 'vip', 'wholesale'])->default('regular');
            $table->date('birth_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};