<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin' => Permission::all(), // Admin gets all permissions
            'Manager' => [
                'list task',
                'create task',
                'read task',
                'update task',
                'delete task',
            ],
            'User' => [
                'read task',
                'update task status',
            ],
        ];
    
        foreach ($roles as $roleName => $permissions) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);
    
            $role->givePermissionTo($permissions);
        }
    }
}
