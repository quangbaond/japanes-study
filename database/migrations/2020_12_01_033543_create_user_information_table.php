<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('image_photo')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('age')->nullable();
            $table->integer('sex')->nullable();
            $table->string('nationality')->nullable();
            $table->string('experience')->nullable();
            $table->longText('self-introduction')->nullable();
            $table->string('membership_status')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('area_code')->nullable();
            $table->string('link_youtube')->nullable();
            $table->string('link_zoom')->nullable();
            $table->text('introduction_from_admin')->nullable();
            $table->string('certification')->nullable();
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
        Schema::dropIfExists('user_information');
    }
}
