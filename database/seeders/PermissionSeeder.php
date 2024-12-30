<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create','description' => 'create']);
        Permission::create(['name'=> 'update','description' => 'update']);
        Permission::create(['name'=> 'delete','description' => 'delete']);

    }
}
