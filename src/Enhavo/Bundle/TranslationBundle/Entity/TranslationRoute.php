<?php

namespace Enhavo\Bundle\TranslationBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

/**
 * TranslationRoute
 */
class TranslationRoute
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @var string
     */
    private $property;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return TranslationRoute
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return RouteInterface
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route = null)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty(string $property): void
    {
        $this->property = $property;
    }
}
