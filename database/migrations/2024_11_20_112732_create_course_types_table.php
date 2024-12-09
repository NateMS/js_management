<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('order')->default(1);
            $table->integer('minimum_age')->nullable();
            $table->integer('maximum_age')->nullable();
            $table->boolean('requires_repetition')->default(1);
            $table->boolean('is_kids_course')->default(0);
            $table->boolean('can_only_attend_once')->default(0);
            $table->foreignId('prerequisite_course_type_id')->nullable()->constrained('course_types')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_types');
    }
};
