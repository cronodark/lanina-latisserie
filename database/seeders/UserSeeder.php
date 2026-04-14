<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdmin = User::factory()->create([
            "email" => "admin@admin.com",
            "password" => bcrypt("password")
        ]);
        $userAdmin->assignRole("admin");

        $userCustomer = User::factory()->create([
            "email" => "customer@example.com",
            "password" => bcrypt("password")
        ]);
        $userCustomer->assignRole("customer");

    }
}
