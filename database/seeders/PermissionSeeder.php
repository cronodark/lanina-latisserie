<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createWebResourcePermissionsFor("products");
        $this->createWebResourcePermissionsFor("promos");
        $this->createWebResourcePermissionsFor("detail_promos");
        $this->createWebResourcePermissionsFor("addresses");
        $this->createWebResourcePermissionsFor("preorders");
        $this->createWebResourcePermissionsFor("detail_preorders");
    }

    public function createWebResourcePermissionsFor(string $resource): void
    {
        Permission::findOrCreate("web.". $resource . ".viewAny", "web");
        Permission::findOrCreate("web.". $resource . ".view", "web");
        Permission::findOrCreate("web.". $resource . ".create", "web");
        Permission::findOrCreate("web.". $resource . ".update", "web");
        Permission::findOrCreate("web.". $resource . ".delete", "web");
    }
}
