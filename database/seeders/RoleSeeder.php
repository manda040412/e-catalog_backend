<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['id_role' => 1, 'role_name' => 'super_admin'],
            ['id_role' => 2, 'role_name' => 'admin'],
            ['id_role' => 3, 'role_name' => 'internal'],
            ['id_role' => 4, 'role_name' => 'eksternal'],
        ]);
    }
}