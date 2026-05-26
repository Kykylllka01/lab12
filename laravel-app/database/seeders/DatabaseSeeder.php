<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем ведущих мастер-классов
        $instructor1 = User::create([
            'name' => 'Иванова Ольга Ивановна',
            'email' => 'instructor1@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79123456789',
            'role' => 'instructor',
        ]);

        $instructor2 = User::create([
            'name' => 'Петров Петр Петрович',
            'email' => 'instructor2@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79123456790',
            'role' => 'instructor',
        ]);

        // Создаем посетителей
        $visitor1 = User::create([
            'name' => 'Сидоров Иван Иванович',
            'email' => 'visitor1@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79123456791',
            'role' => 'visitor',
        ]);

        $visitor2 = User::create([
            'name' => 'Смирнова Анна Петровна',
            'email' => 'visitor2@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79123456792',
            'role' => 'visitor',
        ]);

        // Создаем категории видов творчества
        $category1 = Category::create([
            'name' => 'Архитектурное моделирование',
            'description' => 'Изготовление моделей зданий, сооружений, исторических памятников.',
        ]);

        $category2 = Category::create([
            'name' => 'Кулинария',
            'description' => 'Мастер-классы по приготовлению различных блюд.',
        ]);

        $category3 = Category::create([
            'name' => 'Резьба по дереву',
            'description' => 'Художественная обработка дерева, создание декоративных изделий.',
        ]);

        $category4 = Category::create([
            'name' => 'Выпиливание лобзиком',
            'description' => 'Создание декоративных изделий с помощью лобзика.',
        ]);

        // Создаем мастер-классы
        $mc1 = MasterClass::create([
            'user_id' => $instructor1->id,
            'category_id' => $category4->id,
            'title' => 'Моделирование моделей транспорта',
            'description' => 'Мастер-класс научит основам моделирования различных видов транспортных средств.',
            'date' => '2025-06-05',
            'time' => '15:00',
            'max_participants' => 10,
            'price' => 1500.00,
        ]);

        $mc2 = MasterClass::create([
            'user_id' => $instructor1->id,
            'category_id' => $category4->id,
            'title' => 'Выпиливание фигурок животных',
            'description' => 'Научимся выпиливать красивые фигурки животных из фанеры.',
            'date' => '2025-06-14',
            'time' => '17:00',
            'max_participants' => 8,
            'price' => 1200.00,
        ]);

        $mc3 = MasterClass::create([
            'user_id' => $instructor2->id,
            'category_id' => $category2->id,
            'title' => 'Приготовление стейков',
            'description' => 'Научимся готовить идеальные стейки различной прожарки.',
            'date' => '2025-06-05',
            'time' => '11:00',
            'max_participants' => 6,
            'price' => 2500.00,
        ]);

        // Записываем посетителей на мастер-классы
        \App\Models\Enrollment::create([
            'user_id' => $visitor1->id,
            'master_class_id' => $mc1->id,
        ]);

        \App\Models\Enrollment::create([
            'user_id' => $visitor2->id,
            'master_class_id' => $mc1->id,
        ]);
    }
}
