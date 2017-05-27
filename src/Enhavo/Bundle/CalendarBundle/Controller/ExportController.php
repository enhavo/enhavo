<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function exportAction()
    {
        return $this->get('enhavo_calendar.exporter')->export();
    }

    public function feedAction(Request $request)
    {
        if(!$request->query->has('start') || !$request->query->has('end')){
            $response = new Response();
            $response->setStatusCode(400);
            return $response;
        }

        $startDate = new \DateTime($request->get('start'));
        $endDate = new \DateTime($request->get('end'));

        $normalizedAppointments = $this->get('enhavo_calendar.appointment_provider')
            ->getNormalizedAppointments($startDate, $endDate);

        $eventsArray = [];
        /** @var Appointment $normalizedAppointment */
        foreach ($normalizedAppointments as $normalizedAppointment){
            $event = [
                'title' => $normalizedAppointment->getTitle(),
                'start' => $normalizedAppointment->getDateFrom()->format('Y-m-d H:i:s'),
                'end' => $normalizedAppointment->getDateTo()->format('Y-m-d H:i:s'),
            ];
            $eventsArray[] = $event;
        }

        return new Response(json_encode($eventsArray));
    }
}