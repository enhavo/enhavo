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
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as BaseRouteModel;

class Route extends BaseRouteModel implements RouteInterface, ResourceInterface
{
    private ?string $contentClass = null;
    private ?int $contentId = null;

    public function getContentClass(): ?string
    {
        return $this->contentClass;
    }

    public function setContentClass(string $contentClass): void
    {
        $this->contentClass = $contentClass;
    }

    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    public function setContentId(?int $contentId): void
    {
        $this->contentId = $contentId;
    }

    public function setPath(?string $pattern): static
    {
        $prefix = $pattern;
        $variablePattern = '';

        $position = strpos($pattern, '/{');

        if ($position !== false) {
            $prefix = substr($pattern, 0, $position);
            $variablePattern = substr($pattern, $position);
        }

        if ($prefix) {
            $this->setStaticPrefix($prefix);
        }

        if ($variablePattern) {
            $this->setVariablePattern($variablePattern);
        }

        return $this;
    }
}
