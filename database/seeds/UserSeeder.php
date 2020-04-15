<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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
            $user = [
                'developer' => [
                    'username' => 'rioprastiawan',
                    'name' => 'Rio Prastiawan',
                    'email' => 'akunviprio@gmail.com',
                    'password' => bcrypt('rioprastiawan'),
                    'phone' => '628990125338'
                ],
                'admin' => [
                    'username' => 'admin',
                    'name' => 'Administrator',
                    'email' => 'admin@servicecenter.co.id',
                    'password' => bcrypt('admin'),
                    'phone' => '6281233870774'
                ]
            ];

            $developer = User::create($user['developer']);
            $developer->assignRole('developer');

            $admin = User::create($user['admin']);
            $admin->assignRole('admin');

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
