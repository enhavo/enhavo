<?php
/**
 * Route.php
 *
 * @since 17/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Entity;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as RouteModel;

class Route extends RouteModel implements RouteInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $typeId;
    
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
}
