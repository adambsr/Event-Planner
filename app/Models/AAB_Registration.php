<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AAB_Registration extends Model
{
    protected $table = 'aab_registrations';

    protected $fillable = [
        'user_id',
        'event_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(AAB_Event::class, 'event_id');
    }
}

