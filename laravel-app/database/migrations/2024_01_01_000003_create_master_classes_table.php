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
        Schema::create('master_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ведущий мастер-класса
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Вид творчества
            $table->string('title'); // Название мастер-класса
            $table->text('description'); // Описание мастер-класса
            $table->date('date'); // Дата проведения
            $table->time('time'); // Время начала (9:00, 11:00, 13:00, 15:00)
            $table->integer('max_participants'); // Количество человек в группе
            $table->decimal('price', 10, 2); // Стоимость мастер-класса
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_classes');
    }
};
