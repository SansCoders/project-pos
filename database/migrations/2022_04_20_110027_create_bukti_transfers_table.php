<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuktiTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukti_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("invoices_id");
            $table->unsignedBigInteger("user_id");
            $table->enum("user_type", ['admin', 'cashier', 'user'])->default('user');
            $table->unsignedBigInteger("bank_info_id");
            $table->string("bukti_transfer_image_path");
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('bukti_transfers');
    }
}
