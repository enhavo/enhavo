<?php
/**
 * Route.php
 *
 * @since 17/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Entity;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as RouteModel;

class Route extends RouteModel implements RouteInterface
{
    /**
     * @var string
     */
    protected $host = "";

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $typeId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $schemes = array();

    /**
     * @var array
     */
    protected $methods = array();

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var array
     */
    protected $requirements = array();

    /**
     * @var array
     */
    protected $options = array();

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
