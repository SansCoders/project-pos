<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id');
            $table->string('products_id');
            $table->string('buy_values');
            $table->enum('status', ['pending', 'done']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_transactions');
    }
}
