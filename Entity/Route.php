<?php
/**
 * Route.php
 *
 * @since 17/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Entity;

use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as RouteModel;

class Route extends RouteModel
{
    /**
     * @var string
     */
    private $host = "";

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $typeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $schemes = array();

    /**
     * @var array
     */
    private $methods = array();

    /**
     * @var array
     */
    private $defaults = array();

    /**
     * @var array
     */
    private $requirements = array();

    /**
     * @var array
     */
    private $options = array();

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param mixed $typeId
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
