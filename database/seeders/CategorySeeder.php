<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Room', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Equipment', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vehicle', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
} 