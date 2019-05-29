<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 21:01
 */

namespace Enhavo\Bundle\TemplateBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class TemplateController extends ResourceController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoTemplateBundle:Template:show.html.twig', array(
            'data' => $contentDocument
        ));
    }
}


