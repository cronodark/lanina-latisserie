<?php

namespace Database\Seeders;

use App\Models\PreOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PreOrder::factory(5)->create();
    }
}
