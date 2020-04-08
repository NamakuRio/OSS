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
                'username' => 'rioprastiawan',
                'name' => 'Rio Prastiawan',
                'email' => 'akunviprio@gmail.com',
                'password' => bcrypt('rioprastiawan'),
                'phone' => '628990125338'
            ];

            $create = User::create($user);
            $create->assignRole('developer');

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
