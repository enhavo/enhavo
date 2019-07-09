<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AppointmentController extends AbstractController
{
    use TemplateTrait;

    public function showResourceAction($contentDocument)
    {
        return $this->render($this->getTemplate('theme/resource/appointment/show.html.twig'), array(
            'resource' => $contentDocument
        ));
    }

    public function showAction(Request $request)
    {
        $article = $this->get('enhavo_calendar.repository.appointment')->findOneBy([
            'slug' => $request->get('slug')
        ]);

        if($article === null) {
            $this->createNotFoundException();
        }

        return $this->showResourceAction($article);
    }
}
