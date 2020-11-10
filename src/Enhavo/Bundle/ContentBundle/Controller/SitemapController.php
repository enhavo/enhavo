<?php

namespace Enhavo\Bundle\ContentBundle\Controller;

use Enhavo\Bundle\ContentBundle\Sitemap\SitemapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    /** @var SitemapGenerator */
    private $sitemapGenerator;

    /**
     * SitemapController constructor.
     * @param SitemapGenerator $sitemapGenerator
     */
    public function __construct(SitemapGenerator $sitemapGenerator)
    {
        $this->sitemapGenerator = $sitemapGenerator;
    }

    public function showAction()
    {
        $response =  new Response($this->sitemapGenerator->generate());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
