<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AppointmentController extends AbstractController
{
    use TemplateTrait;

    public function showResourceAction($contentDocument, bool $preview = false)
    {
        if (!$contentDocument->isPublished() && !$preview) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->getTemplate('theme/resource/appointment/show.html.twig'), [
            'resource' => $contentDocument
        ]);
    }

    public function showAction(Request $request)
    {
        $appointment = $this->get('enhavo_calendar.repository.appointment')->findOneBy([
            'slug' => $request->get('slug')
        ]);

        if($appointment === null) {
            throw $this->createNotFoundException();
        }

        return $this->showResourceAction($appointment);
    }
}
