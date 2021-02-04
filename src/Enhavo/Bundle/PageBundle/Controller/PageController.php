<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\PageBundle\Model\PageInterface;

class PageController extends ResourceController
{
    use TemplateTrait;

    public function showResourceAction(Page $contentDocument)
    {
        if (!$contentDocument->isPublished()) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->getTemplate('theme/resource/page/show.html.twig'), array(
            'data' => $contentDocument
        ));
    }
}
