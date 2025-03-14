<?php

namespace Enhavo\Bundle\PageBundle\Page;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class PageManager
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly RouterInterface $router
    )
    {
    }

    public function getPagePath($special, $parameters, $referenceType)
    {
        $page = $this->pageRepository->findOneBy([
            'special' => $special
        ]);

        if(!$page instanceof Page) {
            return $this->getDefaultLink($special);
        }

        if($page->getRoute() === null) {
            return $this->getDefaultLink($special);
        }

        try {
            return $this->router->generate($page->getRoute(), $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            return $this->getDefaultLink($special);
        }
    }

    private function getDefaultLink($special)
    {
        return sprintf('#%s', $special);
    }
}
