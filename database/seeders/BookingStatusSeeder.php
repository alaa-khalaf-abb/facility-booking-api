<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('booking_statuses')->insert([
            ['status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'rejected', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
