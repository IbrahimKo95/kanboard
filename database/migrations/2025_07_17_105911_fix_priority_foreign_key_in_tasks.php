<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);

            $table->foreign('priority_id')
                  ->references('id')
                  ->on('priorities')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);

            $table->foreign('priority_id')
                  ->references('id')
                  ->on('priority');
        });
    }
};

