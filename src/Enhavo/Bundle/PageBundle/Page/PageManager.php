<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Page;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class PageManager
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly RouterInterface $router,
    ) {
    }

    public function getPagePath($special, $parameters, $referenceType)
    {
        $page = $this->pageRepository->findOneBy([
            'special' => $special,
        ]);

        if (!$page instanceof Page) {
            return $this->getDefaultLink($special);
        }

        if (null === $page->getRoute()) {
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
