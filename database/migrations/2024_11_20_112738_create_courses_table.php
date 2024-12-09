<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_nr')->nullable();
            $table->string('name');
            $table->foreignId('course_type_id')->constrained('course_types');
            $table->string('location');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('prerequisites')->nullable();
            $table->date('registration_deadline');
            $table->boolean('is_hidden')->default(0);
            $table->text('notes')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
