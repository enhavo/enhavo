<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-20
 * Time: 09:06
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Mock;


use Closure;
use Doctrine\Common\Proxy\Proxy;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;

class RouteContentMock implements Proxy, Slugable
{
    private $id = 999;

    private $slug;
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function __setInitialized($initialized)
    {

    }

    public function __setInitializer(Closure $initializer = null)
    {

    }

    public function __getInitializer()
    {
        return null;
    }

    public function __setCloner(Closure $cloner = null)
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
