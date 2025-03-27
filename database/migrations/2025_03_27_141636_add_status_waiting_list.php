<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->enum('status', ['signed_up', 'registered', 'attended', 'cancelled', 'waiting_list'])
                ->default('signed_up')
                ->change();
            $table->dateTime('waiting_list_at')->nullable()->after('cancelled_at');
        });

    }

    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->enum('status', ['signed_up', 'registered', 'attended', 'cancelled'])
                ->default('signed_up')
                ->change();
            $table->dropColumn('waiting_list_at');
        });
    }
};