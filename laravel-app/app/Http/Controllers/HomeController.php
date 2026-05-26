<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\MasterClass;
use App\Models\Enrollment;

class HomeController extends Controller
{
    /**
     * Главная страница
     */
    public function index()
    {
        $categories = Category::all();
        
        // Для авторизованных пользователей - список их записей
        $userEnrollments = null;
        if (Auth::check()) {
            $userEnrollments = Enrollment::with('masterClass.category', 'masterClass.instructor')
                ->where('user_id', Auth::id())
                ->get();
        }
        
        return view('home', compact('categories', 'userEnrollments'));
    }
}
