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

    public function getPagePath($special, $parameters, $referenceType): ?string
    {
        $page = $this->pageRepository->findOneBy([
            'special' => $special,
        ]);

        if (!$page instanceof Page) {
            return null;
        }

        if (null === $page->getRoute()) {
            return null;
        }

        try {
            return $this->router->generate($page->getRoute(), $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            return null;
        }
    }
}
