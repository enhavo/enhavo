<?php


namespace Enhavo\Bundle\RoutingBundle\Metadata;

/**
 * Metadata.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class Metadata
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Generator[]
     */
    private $generators = [];

    /**
     * @var Router[]
     */
    private $router = [];

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return Generator[]
     */
    public function getGenerators()
    {
        return $this->generators;
    }

    /**
     * @param Generator[] $generators
     */
    public function setGenerators($generators)
    {
        $this->generators = $generators;
    }

    /**
     * @return Router[]
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router[] $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }
}