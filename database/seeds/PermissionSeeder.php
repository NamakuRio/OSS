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

                ['name' => 'customer.create'],
                ['name' => 'customer.view'],
                ['name' => 'customer.update'],
                ['name' => 'customer.delete'],

                ['name' => 'order.create'],
                ['name' => 'order.view'],
                ['name' => 'order.update'],
                ['name' => 'order.delete'],
                ['name' => 'order.cost'],
                ['name' => 'order.comment'],
                ['name' => 'order.status'],
            ];

            foreach ($permissions as $key => $permission) {
                $create = Permission::create($permission);
                $create->assignRole('developer');

                if ($key > 8 && $key != 13 && $key != 21) {
                    $create->assignRole('admin');

                    if($key == 15 || $key == 19 || $key == 22 || $key == 23){
                        $create->assignRole('teknisi');
                    }

                    if($key >= 14 && $key <= 20) {
                        $create->assignRole('customer_service');
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
