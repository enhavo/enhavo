<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-20
 * Time: 09:06
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Mock;


use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

class RouteContentMock
{
    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $subTitle;

    /**
     * @var RouteInterface
     */
    private $redirectRoute;

    /**
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return RouteInterface
     */
    public function getRedirectRoute(): RouteInterface
    {
        return $this->redirectRoute;
    }

    /**
     * @param RouteInterface $redirectRoute
     */
    public function setRedirectRoute(RouteInterface $redirectRoute): void
    {
        $this->redirectRoute = $redirectRoute;
    }
}
