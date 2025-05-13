<?php

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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \Resource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param \Resource[] $resources
     */
    public function addResource($resources)
    {
        $this->resources->add($resources);
    }

    /**
     * @param $resources
     */
    public function removeResource($resources)
    {
        $this->resources->removeElement($resources);
    }
}
