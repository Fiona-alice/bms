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
           Schema::create('purchase_costs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
        $table->enum('cost_type', [
            'freight',
            'import_duty',
            'vat',
            'clearing_forwarding',
            'port_handling',
            'inland_transport',
            'insurance',
            'miscellaneous',
        ]);
        $table->string('description')->nullable();
        $table->decimal('amount', 15, 4)->default(0);
        $table->timestamps();
    });

    // Also add landed_unit_cost column to purchases
    Schema::table('purchases', function (Blueprint $table) {
        $table->decimal('landed_unit_cost', 15, 6)->nullable()->after('cost_price');
        $table->decimal('extra_costs_total', 15, 4)->default(0)->after('landed_unit_cost');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_costs');
    }
};
