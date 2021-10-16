<?php

namespace Enhavo\Bundle\PageBundle\Page;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class PageManager
{
    /** @var PageRepository */
    private $pageRepository;

    /** @var RouterInterface */
    private $router;

    /**
     * PageManager constructor.
     * @param PageRepository $pageRepository
     * @param RouterInterface $router
     */
    public function __construct(PageRepository $pageRepository, RouterInterface $router)
    {
        $this->pageRepository = $pageRepository;
        $this->router = $router;
    }

    public function getPagePath($code, $parameters, $referenceType)
    {
        $page = $this->pageRepository->findOneBy([
            'code' => $code
        ]);

        if(!$page instanceof Page) {
            return $this->getDefaultLink($code);
        }

        if($page->getRoute() === null) {
            return $this->getDefaultLink($code);
        }

        try {
            return $this->router->generate($page->getRoute(), $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            return $this->getDefaultLink($code);
        }
    }

    private function getDefaultLink($code)
    {
        return sprintf('#%s', $code);
    }
}
