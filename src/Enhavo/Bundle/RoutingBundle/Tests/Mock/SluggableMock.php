<?php

namespace Enhavo\Bundle\RoutingBundle\Tests\Mock;

use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class SluggableMock implements ResourceInterface, Slugable
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
