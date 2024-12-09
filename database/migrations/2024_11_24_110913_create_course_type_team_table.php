<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTypeTeamTable extends Migration
{
    public function up()
    {
        Schema::create('course_type_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_type_id')->constrained('course_types')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_type_team');
    }
}
