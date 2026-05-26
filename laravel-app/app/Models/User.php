<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Получить мастер-классы, которые ведет пользователь
     */
    public function masterClasses()
    {
        return $this->hasMany(MasterClass::class);
    }

    /**
     * Получить записи пользователя на мастер-классы
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Проверка, является ли пользователь ведущим
     */
    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }
}
