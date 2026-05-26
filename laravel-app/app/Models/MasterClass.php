<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model
{
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
        'max_participants' => 'integer',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'master_class_id', 'user_id')
            ->withTimestamps();
    }

    public function availableSpots()
    {
        return $this->max_participants - $this->enrollments()->count();
    }

    public function isFull()
    {
        return $this->availableSpots() <= 0;
    }
}
