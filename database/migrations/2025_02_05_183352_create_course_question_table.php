<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('course_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['course_id', 'question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_questions');
    }
}
