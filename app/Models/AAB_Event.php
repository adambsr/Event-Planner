<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAB_Event extends Model
{
    use HasFactory;

    protected $table = 'aab_events';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'place',
        'price',
        'category_id',
        'capacity',
        'image',
        'created_by',
        'is_free',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(AAB_Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(AAB_Registration::class, 'event_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'aab_registrations', 'event_id', 'user_id')
                    ->withTimestamps();
    }

    public function getAvailablePlacesAttribute()
    {
        return $this->capacity - $this->registrations()->count();
    }

    public function isFull()
    {
        return $this->available_places <= 0;
    }
}

