<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\MasterClass;
use App\Models\Enrollment;

class CategoryController extends Controller
{
    /**
     * Показать страницу категории с мастер-классами
     */
    public function show($id)
    {
        $category = Category::with('masterClasses.instructor')->findOrFail($id);
        $categories = Category::all();
        
        return view('categories.show', compact('category', 'categories'));
    }

    /**
     * Запись на мастер-класс (показать форму подтверждения)
     */
    public function enroll($masterClassId)
    {
        // Только для авторизованных пользователей
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Для записи необходимо авторизоваться.');
        }

        $masterClass = MasterClass::with('instructor', 'category')->findOrFail($masterClassId);
        
        // Проверка: не записан ли уже пользователь
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('master_class_id', $masterClassId)
            ->exists();
        
        if ($alreadyEnrolled) {
            return back()->with('error', 'Вы уже записаны на этот мастер-класс.');
        }
        
        // Проверка наличия мест
        if (!$masterClass->hasAvailableSeats()) {
            return back()->with('error', 'Нет свободных мест на этот мастер-класс.');
        }

        return view('categories.confirm-enrollment', compact('masterClass'));
    }

    /**
     * Подтверждение записи на мастер-класс
     */
    public function confirmEnroll(Request $request, $masterClassId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $masterClass = MasterClass::findOrFail($masterClassId);
        
        // Проверка наличия мест
        if (!$masterClass->hasAvailableSeats()) {
            return back()->with('error', 'Нет свободных мест на этот мастер-класс.');
        }
        
        // Проверка: не записан ли уже пользователь
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('master_class_id', $masterClassId)
            ->exists();
        
        if ($alreadyEnrolled) {
            return back()->with('error', 'Вы уже записаны на этот мастер-класс.');
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'master_class_id' => $masterClassId,
        ]);

        return redirect()->route('category.show', $masterClass->category_id)
            ->with('success', 'Вы успешно записались на мастер-класс!');
    }

    /**
     * Отмена записи на мастер-класс
     */
    public function cancelEnroll(Request $request, $masterClassId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        Enrollment::where('user_id', Auth::id())
            ->where('master_class_id', $masterClassId)
            ->delete();

        return redirect()->route('category.show', $masterClassId)
            ->with('info', 'Запись отменена.');
    }
}
