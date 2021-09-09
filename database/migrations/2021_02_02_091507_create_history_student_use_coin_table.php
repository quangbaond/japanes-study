<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryStudentUseCoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_student_use_coin', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('coin');
            $table->integer('teacher_id')->nullable();
            $table->integer('status');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_student_use_coin');
    }
}
