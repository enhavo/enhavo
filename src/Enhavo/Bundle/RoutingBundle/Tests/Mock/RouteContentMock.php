<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Mock;

use Doctrine\Common\Proxy\Proxy;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;

class RouteContentMock implements Proxy, Slugable
{
    private ?int $id = 999;
    private ?string $slug = null;
    private ?RouteInterface $route = null;
    private ?string $title = null;
    private ?string $subTitle = null;
    private ?RouteInterface $redirectRoute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(?RouteInterface $route): void
    {
        $this->route = $route;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(?string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    public function getRedirectRoute(): ?RouteInterface
    {
        return $this->redirectRoute;
    }

    public function setRedirectRoute(?RouteInterface $redirectRoute): void
    {
        $this->redirectRoute = $redirectRoute;
    }

    public function __setInitialized($initialized)
    {
    }

    public function __setInitializer(?\Closure $initializer = null)
    {
    }

    public function __getInitializer()
    {
        return null;
    }

    public function __setCloner(?\Closure $cloner = null)
    {
    }

    public function __getCloner()
    {
        return null;
    }

    public function __getLazyProperties()
    {
        return null;
    }

    public function __load()
    {
    }

    public function __isInitialized()
    {
        return false;
    }
}
