<?php

namespace Enhavo\Bundle\RoutingBundle\Tests\Mock;

use Enhavo\Bundle\RoutingBundle\Model\Slugable;

class SluggableMock implements Slugable
{
    public $id;

    /** @var string|null */
    private $slug;

    /** @var string|null */
    private $name;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
