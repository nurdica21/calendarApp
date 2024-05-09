<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfAbsence extends Model
{
    use HasFactory;

    protected $table = 'type_of_absence';
    const VACATION = 'Vacation';

    protected $fillable = [
        'title', 'max_num_day', 'is_weekend_allowed'
    ];

}
