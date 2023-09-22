<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageController extends ResourceController
{
    public function showResourceAction(Page $contentDocument, bool $preview)
    {
        if (!$contentDocument->isPublished() && !$preview) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->resolveTemplate('theme/resource/page/show.html.twig'), array(
            'resource' => $contentDocument
        ));
    }
}
