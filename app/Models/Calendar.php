<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_of_absence_id', 'start_date', 'end_date'
    ];

    public function typeOfAbsence()
    {
        return $this->HasOne(TypeOfAbsence::class, 'id', 'type_of_absence_id');
    }
}
