<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('receiver_id')->nullable()->change();

            $table->string('email')->nullable()->after('receiver_id');

            $table->string('token')->unique()->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('token');

            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        });
    }
};
