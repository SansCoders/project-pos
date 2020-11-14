<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices__customs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->enum("user_type", ['admin', 'cashier', 'user'])->default('user');
            $table->bigInteger("product_id");
            $table->bigInteger("prices_c");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices__customs');
    }
}
