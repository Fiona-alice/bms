<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           Unit::insert([
        ['name' => 'Piece', 'symbol' => 'pcs'],
        ['name' => 'Each', 'symbol' => 'ea'],
        ['name' => 'Kilogram', 'symbol' => 'kg'],
        ['name' => 'Liter', 'symbol' => 'L'],
        ['name' => 'Box', 'symbol' => 'box'],
        ['name' => 'Dozen', 'symbol' => 'doz'],
        ['name' => 'Roll', 'symbol' => 'roll'],
        ['name' => 'Pair', 'symbol' => 'pair'],
        ['name' => 'Packet', 'symbol' => 'packet'],
        ['name' => 'Meter', 'symbol' => 'm'],
        ['name' => 'Jerrican', 'symbol' => 'jcan'],
        ['name' => 'Bag', 'symbol' => 'bag'],
        ['name' => 'Activity', 'symbol' => 'act'],
        ['name' => 'Bundle', 'symbol' => 'bundle'],
        ['name' => 'Meter', 'symbol' => 'm'],
        ['name' => 'Bucket', 'symbol' => 'bucket'],
        ['name' => 'Crate', 'symbol' => 'crate'],
        ['name' => 'Gram', 'symbol' => 'g'],
        ['name' => 'Lot', 'symbol' => 'lot'],
        ['name' => 'Set', 'symbol' => 'set'],
        ['name' => 'Millitre', 'symbol' => 'ml'],
    ]);

    }
}
