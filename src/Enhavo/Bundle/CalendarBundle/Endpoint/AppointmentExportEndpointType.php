<?php

namespace Enhavo\Bundle\CalendarBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\CalendarBundle\Export\CalendarExporter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentExportEndpointType
{
    public function __construct(
        private CalendarExporter $calendarExporter,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $content = $this->calendarExporter->export();

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/calendar');
        $context->setResponse($response);
    }
}
