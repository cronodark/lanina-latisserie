<?php

namespace Database\Factories;

use App\Models\TanggalTersedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TanggalTersedia>
 */
class TanggalTersediaFactory extends Factory
{
    protected $model = TanggalTersedia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->dateTimeBetween('now', '+2 months'),
            'kuota' => $this->faker->numberBetween(5, 20),
            'keterangan' => $this->faker->optional()->sentence(),
            'is_aktif' => true,
        ];
    }

    /**
     * Indicate that the slot is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_aktif' => false,
        ]);
    }

    /**
     * Indicate that the slot is for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal' => $date,
        ]);
    }

    /**
     * Indicate that the slot has a specific quota.
     */
    public function withQuota(int $kuota): static
    {
        return $this->state(fn (array $attributes) => [
            'kuota' => $kuota,
        ]);
    }
}
