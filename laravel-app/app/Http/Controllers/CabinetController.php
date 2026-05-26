<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterClass;
use App\Models\Category;
use App\Models\Enrollment;

class CabinetController extends Controller
{
    /**
     * Личный кабинет ведущего
     */
    public function index()
    {
        // Только для ведущих
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403, 'Доступ только для ведущих мастер-классов.');
        }

        $instructor = Auth::user();
        $masterClasses = MasterClass::with(['category', 'enrollments.user'])
            ->where('user_id', $instructor->id)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('cabinet.index', compact('instructor', 'masterClasses'));
    }

    /**
     * Показать форму добавления мастер-класса
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $categories = Category::all();
        $timeSlots = ['09:00', '11:00', '13:00', '15:00']; // Сетка занятий

        return view('cabinet.create', compact('categories', 'timeSlots'));
    }

    /**
     * Сохранение нового мастер-класса
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|in:09:00,11:00,13:00,15:00',
            'max_participants' => 'required|integer|min:1|max:50',
            'price' => 'required|numeric|min:0',
        ], [
            'category_id.required' => 'Выберите вид творчества.',
            'category_id.exists' => 'Неверный вид творчества.',
            'title.required' => 'Название мастер-класса обязательно.',
            'description.required' => 'Описание мастер-класса обязательно.',
            'date.required' => 'Дата обязательна.',
            'date.after_or_equal' => 'Дата должна быть сегодня или в будущем.',
            'time.required' => 'Время обязательно.',
            'time.in' => 'Время должно быть одним из: 9:00, 11:00, 13:00, 15:00.',
            'max_participants.required' => 'Количество участников обязательно.',
            'max_participants.min' => 'Минимум 1 участник.',
            'price.required' => 'Стоимость обязательна.',
            'price.min' => 'Стоимость не может быть отрицательной.',
        ]);

        // Проверка занятости слота времени
        if (MasterClass::isTimeSlotOccupied($validated['date'], $validated['time'])) {
            return back()
                ->withErrors(['time' => 'Это время уже занято на выбранную дату.'])
                ->withInput();
        }

        MasterClass::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'max_participants' => $validated['max_participants'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('cabinet.index')
            ->with('success', 'Мастер-класс успешно создан!');
    }

    /**
     * Показать форму редактирования мастер-класса
     */
    public function edit($id)
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $masterClass = MasterClass::findOrFail($id);
        
        // Проверка: принадлежит ли мастер-класс текущему пользователю
        if ($masterClass->user_id !== Auth::id()) {
            abort(403, 'Вы можете редактировать только свои мастер-классы.');
        }

        $categories = Category::all();
        $timeSlots = ['09:00', '11:00', '13:00', '15:00'];

        return view('cabinet.edit', compact('masterClass', 'categories', 'timeSlots'));
    }

    /**
     * Обновление мастер-класса
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $masterClass = MasterClass::findOrFail($id);
        
        // Проверка: принадлежит ли мастер-класс текущему пользователю
        if ($masterClass->user_id !== Auth::id()) {
            abort(403, 'Вы можете редактировать только свои мастер-классы.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|in:09:00,11:00,13:00,15:00',
            'max_participants' => 'required|integer|min:1|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        // Проверка занятости слота времени (исключая текущий мастер-класс)
        if (MasterClass::isTimeSlotOccupied($validated['date'], $validated['time'], $id)) {
            return back()
                ->withErrors(['time' => 'Это время уже занято на выбранную дату.'])
                ->withInput();
        }

        $masterClass->update($validated);

        return redirect()->route('cabinet.index')
            ->with('success', 'Мастер-класс успешно обновлен!');
    }

    /**
     * Удаление мастер-класса
     */
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $masterClass = MasterClass::findOrFail($id);
        
        if ($masterClass->user_id !== Auth::id()) {
            abort(403, 'Вы можете удалять только свои мастер-классы.');
        }

        $masterClass->delete();

        return redirect()->route('cabinet.index')
            ->with('success', 'Мастер-класс удален.');
    }

    /**
     * Получить занятые слоты времени для даты (AJAX)
     */
    public function getOccupiedSlots(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isInstructor()) {
            abort(403);
        }

        $date = $request->input('date');
        
        $occupiedSlots = MasterClass::getOccupiedTimeSlots($date);
        
        return response()->json(['occupied' => $occupiedSlots]);
    }
}
