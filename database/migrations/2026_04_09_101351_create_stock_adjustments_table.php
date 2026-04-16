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
        Schema::create('stock_adjustments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();

        $table->integer('quantity'); // negative for loss, positive for gain
        $table->decimal('cost_price', 12, 2);
        $table->decimal('total_cost', 12, 2);

        $table->string('type'); // loss, damage, theft, gain, correction
        $table->text('reason')->nullable();

        $table->date('date');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
