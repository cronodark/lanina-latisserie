<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state' => fake('id_ID')->state(),
            'city' => fake('id_ID')->city(),
            'district' => fake('id_ID')->streetName(),
            'street' => fake('id_ID')->streetAddress(),
            'zip_code' => fake('id_ID')->postcode(),
            'rw' => str_pad(fake('id_ID')->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'rt' => str_pad(fake('id_ID')->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'notes' => fake('id_ID')->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}
