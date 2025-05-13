<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Metadata;

/**
 * Metadata.php
 *
 * @since 18/08/18
 *
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

    public function addRouter(Router $router)
    {
        $this->router[] = $router;
    }
}
