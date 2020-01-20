<?php

namespace Enhavo\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    public function showAction()
    {
        $sitemapGenerator = $this->container->get('enhavo_content.sitemap.generator');
        $response =  new Response($sitemapGenerator->generate());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
