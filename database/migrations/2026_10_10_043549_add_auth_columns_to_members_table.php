<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'email')) {
                $table->string('email')->nullable()->unique();
            }
            if (!Schema::hasColumn('members', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('members', 'password')) {
                $table->string('password')->nullable();
            }
            if (!Schema::hasColumn('members', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }

    public function down(): void
    {
        //
    }
};