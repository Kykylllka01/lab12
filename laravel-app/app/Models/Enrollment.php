<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'master_class_id',
    ];

    /**
     * Получить пользователя, записанного на мастер-класс
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить мастер-класс
     */
    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class);
    }
}
