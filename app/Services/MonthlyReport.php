<?php

namespace App\Services;

use App\Http\Resources\TypeOfAbsenceResource;
use App\Models\TypeOfAbsence;
use Carbon\Carbon;
use App\Models\Calendar;

class MonthlyReport
{
    public static function getReportForCurrentMonth()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();

        $absences = Calendar::whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->get();

        $days = [];

        for ($day = 1; $day <= $endDate->day; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);

            $absenceData = $absences->filter(function ($absence) use ($date) {
                return $date->between($absence->start_date, $absence->end_date);
            })->filter(function ($absence) use ($date) {

                return !($date->isWeekend() && $absence->typeOfAbsence->title == TypeOfAbsence::VACATION);
            })->map(function ($absence) {
                return [
                    'id' => $absence->id,
                    'start_date' => $absence->start_date,
                    'end_date' => $absence->end_date,
                    'reason' => new TypeOfAbsenceResource($absence->typeOfAbsence),
                ];
            });

            $dayStructure = [
                'day' => $date->format('l'),
                'short' => $date->format('D'),
                'date' => $date->format('d.m.Y'),
                'absences' => $absenceData,
            ];
            $days[] = $dayStructure;
        }
        return $days;
    }
}