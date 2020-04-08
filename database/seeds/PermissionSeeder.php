<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $permissions = [
                ['name' => 'role.create'],
                ['name' => 'role.view'],
                ['name' => 'role.update'],
                ['name' => 'role.delete'],
                ['name' => 'role.manage'],

                ['name' => 'permission.create'],
                ['name' => 'permission.view'],
                ['name' => 'permission.update'],
                ['name' => 'permission.delete'],

                ['name' => 'user.create'],
                ['name' => 'user.view'],
                ['name' => 'user.update'],
                ['name' => 'user.delete'],
                ['name' => 'user.manage'],
            ];

            foreach ($permissions as $permission) {
                $create = Permission::create($permission);
                $create->assignRole('developer');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
