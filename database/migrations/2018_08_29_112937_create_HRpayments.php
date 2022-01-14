<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHRpayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HRpayments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount')->nullable();
            $table->integer('HRsID')->nullable();
            $table->tinyInteger('paymentMethod')->nullable();
            $table->integer('boxID')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('HRpayments');
    }
}
