<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('course_id')->constrained('courses');
            $table->enum('status', ['signed_up', 'registered', 'attended', 'cancelled'])->default('signed_up');
            $table->dateTime('signed_up_at')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};
