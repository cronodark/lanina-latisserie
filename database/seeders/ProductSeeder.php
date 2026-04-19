<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
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

        Product::factory()
            ->count(10)
            ->create()
            ->each(function (Product $product, int $index) use ($imagePaths): void {
                $product
                    ->addMedia($imagePaths[$index % count($imagePaths)])
                    ->preservingOriginal()
                    ->toMediaCollection(Product::MEDIA_COLLECTION);
            });
    }
}
