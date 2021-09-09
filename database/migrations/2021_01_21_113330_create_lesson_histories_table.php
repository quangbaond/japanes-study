<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('status_lesson')->nullable();
            $table->integer('lesson_id');
            $table->integer('course_id')->nullable();
            $table->integer('teacher_id');
            $table->date('date');
            $table->time('time');
            $table->string('zoom_link');
            $table->string('coin');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('lesson_histories');
    }
}
