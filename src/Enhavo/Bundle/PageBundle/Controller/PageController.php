<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;

class PageController extends ResourceController
{
    use TemplateTrait;

    public function showResourceAction($contentDocument)
    {
        return $this->render($this->getTemplate('theme/resource/page/show.html.twig'), array(
            'data' => $contentDocument
        ));
    }
}
