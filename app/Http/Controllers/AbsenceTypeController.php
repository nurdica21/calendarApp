<?php

namespace App\Http\Controllers;

use App\Http\Resources\TypeOfAbsenceResource;
use App\Models\TypeOfAbsence;
use Illuminate\Http\Request;

class AbsenceTypeController extends Controller
{
   public function index(){
    return TypeOfAbsenceResource::collection(TypeOfAbsence::all());
   }
}
