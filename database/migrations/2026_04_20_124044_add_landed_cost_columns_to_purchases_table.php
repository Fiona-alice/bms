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
       Schema::table('purchases', function (Blueprint $table) {

        if (!Schema::hasColumn('purchases', 'landed_unit_cost')) {
            $table->decimal('landed_unit_cost', 15, 6)
                  ->nullable()
                  ->after('cost_price');
        }

        if (!Schema::hasColumn('purchases', 'extra_costs_total')) {
            $table->decimal('extra_costs_total', 15, 4)
                  ->default(0)
                  ->after('landed_unit_cost');
        }

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('purchases', function (Blueprint $table) {

        if (Schema::hasColumn('purchases', 'landed_unit_cost')) {
            $table->dropColumn('landed_unit_cost');
        }

        if (Schema::hasColumn('purchases', 'extra_costs_total')) {
            $table->dropColumn('extra_costs_total');
        }

    });
    }
};
