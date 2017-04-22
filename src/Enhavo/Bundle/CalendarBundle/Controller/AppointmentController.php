<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class AppointmentController extends ResourceController
{
    public function exportICSAction()
    {
        return $this->get('enhavo_calendar.exporter')->export();
    }
}
