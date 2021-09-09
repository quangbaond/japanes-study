<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_info', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('payment_method');
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('paypal_subscription_id')->nullable();
            $table->dateTime('trial_start_date')->nullable();
            $table->dateTime('trial_end_date')->nullable();
            $table->dateTime('premium_start_date')->nullable();
            $table->dateTime('premium_end_date')->nullable();
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
        Schema::dropIfExists('user_payment_info');
    }
}
