<?php

use Illuminate\Database\Seeder;

class AboutUs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('about_us')->insert([
            'name' => 'Toko',
            'img_company' => 'assets/img/brand/favicon.png',
            'phone' => null,
            'address' => "Jl.",
            'about' => "is",
        ]);
    }
}
