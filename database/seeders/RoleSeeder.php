<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createCustomerRole();
    }

    public function createAdminRole(): void
    {
        $adminRole = Role::findOrCreate("admin", "web");

        $adminPermissions = [
            // CRUD products
            "web.products.viewAny",
            "web.products.view",
            "web.products.create",
            "web.products.update",
            "web.products.delete",

            // CRUD promos
            "web.promos.viewAny",
            "web.promos.view",
            "web.promos.create",
            "web.promos.update",
            "web.promos.delete",

            // Read + update preorders
            "web.preorders.viewAny",
            "web.preorders.view",
            "web.preorders.update",
        ];

        $adminRole->syncPermissions(
            Permission::where("guard_name", "web")
                ->whereIn("name", $adminPermissions)
                ->get()
        );
    }

    public function createCustomerRole(): void
    {
        $customerRole = Role::findOrCreate("customer", "web");

        $customerPermissions = [
            // CRUD addresses
            "web.addresses.viewAny",
            "web.addresses.view",
            "web.addresses.create",
            "web.addresses.update",
            "web.addresses.delete",

            // Create + read preorders
            "web.preorders.create",
            "web.preorders.viewAny",
            "web.preorders.view",
        ];

        $customerRole->syncPermissions(
            Permission::where("guard_name", "web")
                ->whereIn("name", $customerPermissions)
                ->get()
        );
    }
}
