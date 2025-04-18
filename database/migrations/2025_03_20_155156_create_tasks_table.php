<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamp('due_date');
            $table->timestamp('completed_at');
            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedBigInteger('priority_id')->index();
            $table->foreign('priority_id')->references('id')->on('priority');
            $table->unsignedBigInteger('column_id');
            $table->foreign('column_id')->references('id')->on('columns');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
