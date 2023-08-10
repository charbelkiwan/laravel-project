<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $permissions = [
            'create projects',
            'edit projects',
            'delete projects',
            'create tasks',
            'edit tasks',
            'delete tasks',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $adminRole->givePermissionTo($permission);
        }
        $adminUser = User::where('email', 'charbel_kiwan@outlook.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
