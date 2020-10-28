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
            'username' => 'fakhrads',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('admins')->insert([
            'name' => 'Developer User',
            'username' => 'fakhrads',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('cashiers')->insert([
            'name' => 'Developer User',
            'username' => 'fakhrads',
            'email' => 'user1@email.com',
            'password' => bcrypt('password'),
        ]);
    }
}
