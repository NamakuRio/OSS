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
                ['name' => 'order.handphone'],
                ['name' => 'order.laptop'],
                ['name' => 'order.printer'],
                ['name' => 'order.komputer'],
                ['name' => 'order.powerbank'],
            ];

            foreach ($permissions as $key => $permission) {
                $create = Permission::create($permission);
                $create->assignRole('developer');

                if ($key > 8 && $key != 13 && $key != 21) {
                    $create->assignRole('admin');

                    if($key == 15 || $key == 19 || $key == 22 || $key == 23){
                        $create->assignRole('teknisi_handphone');
                        $create->assignRole('teknisi_laptop');
                        $create->assignRole('teknisi_printer');
                        $create->assignRole('teknisi_komputer');
                        $create->assignRole('teknisi_powerbank');
                    }

                    if($key >= 14 && $key <= 20) {
                        $create->assignRole('customer_service');
                    }

                    if($key == 25){
                        $create->assignRole('teknisi_handphone');
                        $create->assignRole('customer_service');
                    }
                    if($key == 26){
                        $create->assignRole('teknisi_laptop');
                        $create->assignRole('customer_service');
                    }
                    if($key == 27){
                        $create->assignRole('teknisi_printer');
                        $create->assignRole('customer_service');
                    }
                    if($key == 28){
                        $create->assignRole('teknisi_komputer');
                        $create->assignRole('customer_service');
                    }
                    if($key == 29){
                        $create->assignRole('teknisi_powerbank');
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
