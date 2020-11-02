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
            $table->bigInteger('transaction_id');
            $table->integer('user_id')->nullable();
            $table->string('user_fullname');
            $table->string('cashier_name')->nullable();
            $table->string('products_id')->nullable();
            $table->string('products_list')->nullable();
            $table->string('products_buyvalues');
            $table->string('products_prices');
            $table->enum('type', ['COD'])->nullable();
            $table->boolean('is_done')->default(0);
            $table->timestamp('done_time')->nullable();
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
