<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'date',
        'time',
        'max_participants',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Получить ведущего мастер-класса
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Получить категорию вида творчества
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получить записи на мастер-класс
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Получить участников мастер-класса
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'master_class_id', 'user_id');
    }

    /**
     * Проверить наличие свободных мест
     */
    public function hasAvailableSeats(): bool
    {
        return $this->enrollments()->count() < $this->max_participants;
    }

    /**
     * Получить количество свободных мест
     */
    public function availableSeats(): int
    {
        return max(0, $this->max_participants - $this->enrollments()->count());
    }

    /**
     * Получить время окончания (мастер-класс длится 2 часа)
     */
    public function getEndTimeAttribute(): string
    {
        $startTime = \Carbon\Carbon::parse($this->time);
        return $startTime->addHours(2)->format('H:i');
    }

    /**
     * Проверить, занят ли слот времени для данной даты
     */
    public static function isTimeSlotOccupied($date, $time, $excludeId = null): bool
    {
        $query = self::where('date', $date)
                    ->where('time', $time);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Получить все занятые слоты времени для даты
     */
    public static function getOccupiedTimeSlots($date): array
    {
        return self::where('date', $date)
                    ->pluck('time')
                    ->toArray();
    }
}
