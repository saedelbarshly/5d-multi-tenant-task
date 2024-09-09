<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'list task',
            'create task',
            'read task',
            'update task',
            'update task status',
            'delete task',
            'list user',
            'create user',
            'read user',
            'update user',
            'delete user',
        ];
        
        foreach ($permissions as $permission) {
           Permission::updateOrCreate(
               ['name' => $permission],  // Search criteria
               ['guard_name' => 'api']   // Attributes to update/create
           );
        }
    }
}
