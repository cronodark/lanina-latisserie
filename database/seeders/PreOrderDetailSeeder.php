<?php

namespace Database\Seeders;

use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreOrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preOrder = PreOrder::pluck('id')->toArray();

        foreach ($preOrder as $id) {
            PreOrderDetail::factory(5)->create([
                'pre_order_id' => $id,
            ]);
        }
    }
}
