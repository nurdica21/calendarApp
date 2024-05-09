<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeOfAbsence;

class TypeOfAbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         
         TypeOfAbsence::create([
            'title' => 'Vacation',
            'max_num_day' => 25, 
        ]);

        
        TypeOfAbsence::create([
            'title' => 'Sick Leave',
            'max_num_day' => 7,
        ]);

        
    }
}
