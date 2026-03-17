<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавляем колонку роли после email. По умолчанию - master.
            $table->string('role')->default('master')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Если захотим откатить - удалим колонку
            $table->dropColumn('role');
        });
    }
};