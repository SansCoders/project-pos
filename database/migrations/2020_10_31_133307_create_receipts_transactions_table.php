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
            $table->integer('user_id');
            $table->string('user_name');
            $table->integer('cashier_id')->nullable();
            $table->string('cashier_name')->nullable();
            $table->text('products_id');
            $table->text('products_list');
            $table->text('products_buyvalues');
            $table->text('products_prices');
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
