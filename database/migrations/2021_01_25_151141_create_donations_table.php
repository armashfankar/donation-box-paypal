<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("email")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->char("country_code", 2)->nullable();
            $table->double("total", 10, 2)->nullable();
            $table->double("merchant_fees", 10, 2)->nullable();
            $table->char("currency", 3)->nullable();
            $table->string("payment_method")->nullable();
            $table->string("payment_status")->nullable();
            $table->mediumText("payer_info")->nullable();
            $table->string("event_id")->nullable();
            $table->string("transaction_fee")->nullable();
            $table->string("paypal_created_time")->nullable();
            $table->string("paypal_updated_time")->nullable();
            $table->string("paypal_id")->nullable();
            $table->string("payer_id")->nullable();
            $table->string("transaction_state")->nullable();
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
        Schema::dropIfExists('donations');
    }
}
