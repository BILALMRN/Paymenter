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
        Schema::create('order_product_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\OrderProduct::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\ConfigOption::class)->constrained()->cascadeOnDelete();
            $table->foreignId('config_value_id')->constrained('config_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_configs');
    }
};