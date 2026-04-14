<?php

namespace Database\Seeders;

use App\Models\Promo;
use App\Models\PromoDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promo = Promo::pluck('id')->toArray();

        foreach ($promo as $id) {
            PromoDetail::factory(5)->create([
                'promo_id' => $id,
            ]);
        }
    }
}
