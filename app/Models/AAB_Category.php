<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAB_Category extends Model
{
    use HasFactory;

    protected $table = 'aab_categories';

    protected $fillable = [
        'name'
    ];

    public function events()
    {
        return $this->hasMany(AAB_Event::class, 'category_id');
    }
}

