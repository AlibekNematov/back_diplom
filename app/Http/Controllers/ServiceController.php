<?php

namespace App\Http\Controllers;

use App\Models\ClientService;
use App\Models\Club;
use App\Models\Employee;
use App\Models\EmployeeService;
use App\Models\Service;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function getServicesList(): array
    {
        $serviceType = @$_GET['serviceType'];
        $resultServicesList = [];
        $servicesList = [];

        // Вызывать getPersonalServiceAvailableDates для того, чтобы возвращать флаг наличия кнопки "Записаться"
        if ($serviceType === 'group') $servicesList = Service::all()->where('is_group', 1);
        else if ($serviceType === 'personal') $servicesList = Service::all()->where('is_group', 0);
        else $servicesList = Service::all();

        foreach ($servicesList as $service) {
            $serviceObject = $service;
            $club = Club::query()->find($service['club_id']);

            $serviceObject['employees'] = $service->employees;
            $serviceObject['club_title'] = $club['title'];

            $resultServicesList[] = $serviceObject;
        }

        return $resultServicesList;
    }

    public function getRegistrationsList(int $clientId)
    {
        $resultRegistrationsList = [];
        $registrationsList = array_values(ClientService::all()->where('client_id', $clientId)->toArray());

        foreach ($registrationsList as $registration) {
            $registrationObject = $registration;
            $service = Service::query()->find($registration['service_id']);
            $club = Club::query()->find($service['club_id']);

            $registrationObject['club_title'] = $club['title'];
            $registrationObject['service_title'] = $service['title'];
            $registrationObject['is_group'] = (bool)$service['is_group'];

            $resultRegistrationsList[] = $registrationObject;
        }

        return $resultRegistrationsList;
    }

    public function getAvailableDates(int $id): array
    {
        $service = Service::query()->find($id);

        if (!$service['is_group']) return $this->getPersonalServiceAvailableDates($service);
        else return $this->getGroupServiceAvailableDates();
    }

    public function getAvailableTimeslots(int $id, string $date): array
    {
        $service = Service::query()->find($id);

        if (!$service['is_group']) return $this->getPersonalServiceAvailableTimeslots($service, $date);
        else return $this->getGroupServiceAvailableTimeslots($service, $date);
    }

    public function makeAppointment (Request $request, int $serviceId)
    {
        $body = json_decode($request->getContent(), true);

        if (!$serviceId || !@$body['clientId'] || !@$body['date'] || !@$body['timeslot']) {
            return 'Заполните все необходимые поля!';
        }

        return ClientService::create([
            'service_id' => $serviceId,
            'client_id' => $body['clientId'],
            'timeslot' => $body['timeslot'],
            'date' => $body['date'],
        ]);
    }

    public function deleteAppointment(int $registrationId)
    {
        if (!$registrationId) {
            return 'Не передан идентификатор записи!';
        }

        $appointment = ClientService::query()->find($registrationId);

        return $appointment->delete();
    }

    public function changeAppointment(Request $request, int $registrationId)
    {
        $body = json_decode($request->getContent(), true);
        $appointment = ClientService::query()->find($registrationId);

        return $appointment->update([
            'timeslot' => $body['timeslot'],
            'date' => $body['date'],
        ]);
    }

    private function getPersonalServiceAvailableDates(Service $service): array
    {
        $availableDates = [];
        $club = Club::query()->find($service['club_id']);
        $clubWorkingPeriod = ClubController::getWorkingPeriod($club);
        $clubTimeslotsCount = $clubWorkingPeriod / $service['duration'];

        if ($clubTimeslotsCount < 1) return [];

        $interval = DateInterval::createFromDateString('1 day');
        $startDate = (new DateTime());
        $endDate = (new DateTime())->add(new DateInterval('P7D'));
        $datesRange = new DatePeriod($startDate, $interval, $endDate);

        foreach ($datesRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $registrationsByDate = ClientService::query()
                ->where('service_id', $service['id'])
                ->where('date', $formattedDate)
                ->get()
                ->toArray();

            if (count($registrationsByDate) < $clubTimeslotsCount) $availableDates[] = $formattedDate;
        }

        return $availableDates;
    }

    private function getGroupServiceAvailableDates(): array {
        $availableDates = [];
        $interval = DateInterval::createFromDateString('1 day');
        $startDate = (new DateTime());
        $endDate = (new DateTime())->add(new DateInterval('P7D'));
        $datesRange = new DatePeriod($startDate, $interval, $endDate);

        foreach ($datesRange as $date) $availableDates[] = $date->format('Y-m-d');

        return $availableDates;
    }

    private function getPersonalServiceAvailableTimeslots(Service $service, string $date): array
    {
        $club = Club::query()->find($service['club_id']);
        $registrationsByDate = ClientService::query()
            ->where('service_id', $service['id'])
            ->where('date', $date)
            ->get()
            ->pluck('timeslot')
            ->toArray();

        $availableTimeslots = ClubController::getWorkingTimeslotsRange($club);
        $serviceDurationRange = $service['duration'] === 1
            ? []
            : range(1, $service['duration'] - 1);

        // Учесть последние часы перед окончанием работы клуба
        $lastUnavailableTimeslots = $club['end_working_timeslot'] - ($service['duration'] - 1);
        // Иммитируем "укороченную" запись на услугу перед окончанием времени работы клуба
        $registrationsByDate[] = $lastUnavailableTimeslots < 0
            ? 24 - abs($lastUnavailableTimeslots)
            : $lastUnavailableTimeslots;

        // Недоступные для записи слоты
        $unavailableTimeslots = [];

        // Сделать недоступными слоты перед и после оказани(ем/я) услуги
        foreach ($registrationsByDate as $registrationTimeslot) {
            $unavailableTimeslots[] = $registrationTimeslot;

            foreach ($serviceDurationRange as $serviceDurationStep) {
                // Недоступный слот после таймслота записи
                $unavailableTimeslotPost = $registrationTimeslot + $serviceDurationStep;
                // Недоступный слот до таймслота записи
                $unavailableTimeslotPre = $registrationTimeslot - $serviceDurationStep;

                $unavailableTimeslots[] = $unavailableTimeslotPost > 23
                    ? 24 - $unavailableTimeslotPost
                    : $unavailableTimeslotPost;

                $unavailableTimeslots[] = $unavailableTimeslotPre < 0
                    ? 24 - abs($unavailableTimeslotPre)
                    : $unavailableTimeslotPre;
            }
        }

        $resultTimeslots = array_values(array_diff($availableTimeslots, $unavailableTimeslots));

        return $this->filterTimeslotsByNowMoment($resultTimeslots);
    }

    private function getGroupServiceAvailableTimeslots(Service $service, string $date): array
    {
        $club = Club::query()->find($service['club_id']);

        $availableTimeslots = ClubController::getWorkingTimeslotsRange($club, $service['duration']);

        $resultTimeslots = array_values(array_filter($availableTimeslots, function ($timeslot) use ($club, $service) {
            if ($timeslot < $club['end_working_timeslot'] && $timeslot + $service['duration'] > $club['end_working_timeslot']) return false;

            return true;
        }));

        return $this->filterTimeslotsByNowMoment($resultTimeslots);
    }

    private function filterTimeslotsByNowMoment(array $timeslots): array
    {
        $nowTimeslot = (int)date('H') + 3;

        if ($nowTimeslot > 23) $nowTimeslot = $nowTimeslot - 24;

        return array_values(array_filter($timeslots, function ($timeslot) use ($nowTimeslot) {
            return $timeslot > $nowTimeslot;
        }));
    }
}
