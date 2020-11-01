<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts__transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->integer('user_id')->nullable();
            $table->string('user_fullname');
            $table->string('cashier_name');
            $table->string('products_id')->nullable();
            $table->string('products_list');
            $table->string('products_buyvalues');
            $table->string('products_prices');
            $table->enum('type', ['COD'])->nullable();
            $table->timestamp('done_time');
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
        Schema::dropIfExists('receipts__transactions');
    }
}
