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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent']); // discount type
            $table->decimal('value', 8, 2);
            $table->decimal('min_order_amount', 8, 2)->nullable(); // optional minimum
            $table->integer('usage_limit')->nullable(); // how many times this can be used
            $table->integer('used_count')->default(0);
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
