<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use RuntimeException;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagePaths = array_values(
            glob(public_path('images/*.{jpg,jpeg,png,webp,avif}'), GLOB_BRACE) ?: []
        );

        if (empty($imagePaths)) {
            throw new RuntimeException('No seed images found in public/images.');
        }

        Promo::factory()
            ->count(5)
            ->create()
            ->each(function (Promo $promo, int $index) use ($imagePaths): void {
                $promo
                    ->addMedia($imagePaths[$index % count($imagePaths)])
                    ->preservingOriginal()
                    ->toMediaCollection(Promo::MEDIA_COLLECTION);
            });
    }
}
