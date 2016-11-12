<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageController extends ResourceController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoPageBundle:Page:show.html.twig', array(
            'data' => $contentDocument
        ));
    }
}
