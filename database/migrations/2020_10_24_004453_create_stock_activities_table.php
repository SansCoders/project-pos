<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_stock', function (Blueprint $table) {
            $table->id();
            $table->integer('stocks_id');
            $table->integer('product_id');
            $table->integer('users_id');
            $table->enum('user_type_id', ['admin', 'cashier']);
            $table->enum('type_activity', ['in', 'out', 'add', 'destroy']);
            $table->integer('stock');
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
        Schema::dropIfExists('a_stock');
    }
}
