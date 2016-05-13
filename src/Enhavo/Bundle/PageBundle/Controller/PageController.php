<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageController extends ResourceController
{
    public function showResource(Page $page)
    {
        return $this->render('EnhavoPageBundle:Page:show.html.twig', array(
            'data' => $page
        ));
    }

    public function batchActionPublish($resources)
    {
        $this->isGrantedOr403('edit');
        $em = $this->get('doctrine.orm.entity_manager');
        /** @var Page $page */
        foreach ($resources as $page) {
            if (!$page->getPublic()) {
                $page->setPublic(true);
                $em->persist($page);
            }
        }
        $em->flush();

        return true;
    }
}
