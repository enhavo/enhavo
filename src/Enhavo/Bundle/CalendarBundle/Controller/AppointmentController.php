<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AppointmentController extends AbstractController
{
    use TemplateResolverTrait;

    public function showResourceAction($contentDocument, bool $preview = false)
    {
        if (!$contentDocument->isPublished() && !$preview) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->resolveTemplate('theme/resource/appointment/show.html.twig'), [
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
