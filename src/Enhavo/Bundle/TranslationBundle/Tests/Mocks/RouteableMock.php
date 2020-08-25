<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Mocks;


use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

class RouteableMock extends TranslatableMock implements Routeable
{
    /** @var RouteInterface|null */
    public $route;

    /**
     * @return RouteInterface|null
     */
    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    /**
     * @param RouteInterface|null $route
     */
    public function setRoute(?RouteInterface $route): void
    {
        $this->route = $route;
    }


}
