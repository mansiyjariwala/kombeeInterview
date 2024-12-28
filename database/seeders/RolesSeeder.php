<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin','description' => 'Admin of the application']);
        Role::create(['name'=> 'User','description' => 'User of the application']);
        Role::create(['name'=> 'Supplier','description' => 'Supplier of the application']);
        Role::create(['name'=> 'Customer','description' => 'Customer of the application']);

    }
}
