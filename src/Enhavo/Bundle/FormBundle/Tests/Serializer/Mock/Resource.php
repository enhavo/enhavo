<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Serializer\Mock;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author gseidel
 */
class Resource
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     */
    private $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return resource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param resource[] $resources
     */
    public function addResource($resources)
    {
        $this->resources->add($resources);
    }

    public function removeResource($resources)
    {
        $this->resources->removeElement($resources);
    }
}
