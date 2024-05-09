<?php

namespace App\Http\Controllers;

use App\Services\MonthlyReport;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Http\Response;
use App\Http\Requests\CreateAbsenceRequest;
use App\Http\Resources\TypeOfAbsenceResource;
use App\Models\TypeOfAbsence;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $calendar = MonthlyReport::getReportForCurrentMonth();
        return response()->json($calendar);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAbsenceRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $valid = $this->areAbsenceDaysValid($request);

            if ($valid !== true) {
                return $valid;
            }

            if ($this->hasOverlappingAbsences($validatedData)) {
                return response()->json([
                    'message' => 'The specified period overlaps with an existing absence.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $new_calendar = Calendar::create($validatedData);

            return response()->json([
                'data' => new CalendarResource($new_calendar),
                'message' => 'Successfully added new absence!',
                'status' => Response::HTTP_CREATED
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Failed to add new absence.',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function areAbsenceDaysValid(CreateAbsenceRequest $request)
    {
        $absenceTypeId = $request->get('type_of_absence_id');
        $absenceType = TypeOfAbsence::find($absenceTypeId);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $startDate_c = \Carbon\Carbon::parse($start_date);
        $endDate_c = \Carbon\Carbon::parse($end_date);
        $numberOfAbsenceDays = $startDate_c->diffInWeekdays($endDate_c);

        if ($numberOfAbsenceDays > $absenceType->max_num_day) {
            return response()->json([
                'message' => 'Absence days for ' . $absenceType->title . ' is ' . $absenceType->max_num_day,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($absenceType->title !== TypeOfAbsence::VACATION) {
            return true;
        }

        if (!$this->vacationDatesValid($start_date, $end_date)) {
            return response()->json([
                'message' => 'Vacation cannot include weekend days.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return true;
    }

    protected function vacationDatesValid($start_date, $end_date)
    {
        $startDate = \Carbon\Carbon::parse($start_date);
        $endDate = \Carbon\Carbon::parse($end_date);

        return !($startDate->isWeekend() || $endDate->isWeekend());
    }

    protected function hasOverlappingAbsences($validatedData)
    {
        return Calendar::where(function ($query) use ($validatedData) {
                $query->whereBetween('start_date', [$validatedData['start_date'], $validatedData['end_date']])
                    ->orWhereBetween('end_date', [$validatedData['start_date'], $validatedData['end_date']]);
            })
            ->exists();
    }
}
