<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnTableToReceiptsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts__transactions', function (Blueprint $table) {
            $table->integer('diskon')->default(0)->nullable();
            $table->integer('custom_prices')->default(0)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts__transactions', function (Blueprint $table) {
            //
        });
    }
}
