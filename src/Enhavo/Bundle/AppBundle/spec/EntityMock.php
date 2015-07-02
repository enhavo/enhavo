<?php
/**
 * EntityMock.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\spec;

class EntityMock
{
    private $name;

    public function getId()
    {
        return 1;
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
}