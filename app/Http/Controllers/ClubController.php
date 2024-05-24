<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Club;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function getClubsList(): Collection
    {
        return Club::all();
    }

    public function getClubDetail(int $id): array
    {

        $club = Club::find($id);
        $employees = Employee::where("club_id", $id)->get();
        $clientsCount = Client::where("club_id", $id)->count();

        return [
            "club" => $club,
            "employees" => $employees,
            "clientsCount" => $clientsCount
        ];
    }

    static function getWorkingPeriod(Club $club): int
    {
        // Получить разницу между числами на числовом ряду
        $serviceTimeslotsCount = abs($club['end_working_timeslot'] - $club['start_working_timeslot']);

        // Если таймслот окончания меньше таймслота начала, значит таймслот окончания заходит на следующую дату
        if ($club['end_working_timeslot'] <= $club['start_working_timeslot']) $serviceTimeslotsCount -= 24;

        return abs($serviceTimeslotsCount);
    }

    static function getWorkingTimeslotsRange(Club $club, int $step = 1): array
    {
        $availableTimeslots = [];
        $clubWorkingPeriod = ClubController::getWorkingPeriod($club);

        for ($i = 0; $i <= $clubWorkingPeriod; $i += $step) {
            $timeslot = $i + $club['start_working_timeslot'];

            $availableTimeslots[] = $timeslot > 23 ? $timeslot - 24 : $timeslot;
        }

        sort($availableTimeslots, SORT_ASC);

        return $availableTimeslots;
    }
}
