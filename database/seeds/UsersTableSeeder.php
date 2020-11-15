<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Developer User',
            'username' => 'user',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('profile_users')->insert([
            'fullname' => null,
            'gender' => null,
            'birth_date' => null,
            'photo' => "user-img/user-img-default.png",
            'user_id' => 1,
            'user_type' => 3,
        ]);
        DB::table('admins')->insert([
            'name' => 'Developer User',
            'username' => 'admin',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('cashiers')->insert([
            'name' => 'Developer User',
            'username' => 'kasir',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('about_us')->insert([
            'name' => 'Toko Habibi',
            'img_company' => 'assets/img/brand/favicon.png',
            'phone' => null,
            'address' => "Jl.",
            'about' => "is",
        ]);
    }
}
