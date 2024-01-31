<?php
/**
 * Route.php
 *
 * @since 17/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as RouteModel;

class Route extends RouteModel implements RouteInterface, ResourceInterface
{
    /**
     * @var string
     */
    private $contentClass;

    /**
     * @var int
     */
    private $contentId;

    /**
     * @return string
     */
    public function getContentClass()
    {
        return $this->contentClass;
    }

    /**
     * @param string $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    public function setPath($path)
    {
        $prefix = $path;
        $variablePattern = null;

        $position = strpos($path, '/{');

        if($position !== false) {
            $prefix = substr($path, 0, $position);
            $variablePattern = substr($path, $position);
        }

        $this->setStaticPrefix($prefix);
        $this->setVariablePattern($variablePattern);
    }

    public function generateRouteName()
    {
        $this->setName('r' . uniqid());
    }
}
