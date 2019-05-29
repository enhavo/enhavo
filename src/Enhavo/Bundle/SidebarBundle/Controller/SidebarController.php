<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 21:01
 */

namespace Enhavo\Bundle\SidebarBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class SidebarController extends ResourceController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoSidebarBundle:Sidebar:show.html.twig', array(
            'data' => $contentDocument
        ));
    }
}


