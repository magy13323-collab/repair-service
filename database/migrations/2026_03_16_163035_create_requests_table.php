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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('clientName'); // Имя клиента
            $table->string('phone');      // Телефон
            $table->string('address');    // Адрес
            $table->text('problemText');  // Описание проблемы
            // Статус с вариантами из ТЗ, по умолчанию - "new"
            $table->enum('status', ['new', 'assigned', 'in_progress', 'done', 'canceled'])->default('new');
            // Мастер, на которого назначено (может быть пустым)
            $table->foreignId('assignedTo')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps(); // Поля createdAt и updatedAt (Laravel сделает их сам)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
