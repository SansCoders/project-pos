<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => rand(1, 5),
        'kodebrg' => 'ABC' . rand(1, 5),
        'nama_product' => $faker->name,
        'description' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aliquid aperiam debitis, perspiciatis, impedit placeat quis, facilis voluptates reprehenderit et in veniam?',
        'price' => rand(10000, 50000),
        'img' => 'https://freepngimg.com/thumb/food/6-2-food-png-clipart-thumb.png'
    ];
});
