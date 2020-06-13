<?php


namespace Enhavo\Bundle\RoutingBundle\Metadata;

/**
 * Metadata.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class Metadata extends \Enhavo\Component\Metadata\Metadata
{

    /**
     * @var Generator[]
     */
    private $generators = [];

    /**
     * @var Router[]
     */
    private $router = [];

    /**
     * @return Generator[]
     */
    public function getGenerators()
    {
        return $this->generators;
    }

    /**
     * @param Generator $generator
     */
    public function addGenerator(Generator $generator)
    {
        $this->generators[] = $generator;
    }


    /**
     * @return Router[]
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function addRouter(Router $router)
    {
        $this->router[] = $router;
    }
}
