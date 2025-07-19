<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
               'name'=>'User',
               'email'=>'user@mekkawy.com',
               'type'=>0,
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'Super Admin',
               'email'=>'admin@mekkawy.com',
               'type'=>1,
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'Manager',
               'email'=>'manager@mekkawy.com',
               'type'=> 2,
               'password'=> bcrypt('Mekkawy@0024'),
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
