<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
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
            $roles = [
                ['name' => 'developer', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'admin', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'teknisi_handphone', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'teknisi_laptop', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'teknisi_printer', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'teknisi_komputer', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'teknisi_powerbank', 'default_user' => 0, 'login_destination' => '/'],
                ['name' => 'customer_service', 'default_user' => 0, 'login_destination' => '/'],
            ];

            foreach ($roles as $role) {
                Role::create($role);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
