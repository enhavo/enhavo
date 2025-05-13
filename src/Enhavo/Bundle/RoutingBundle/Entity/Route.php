<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as BaseRouteModel;

class Route extends BaseRouteModel implements RouteInterface
{
    private ?string $contentClass = null;
    private ?int $contentId = null;

    public function getContentClass(): ?string
    {
        return $this->contentClass;
    }

    public function setContentClass(?string $contentClass): void
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

        if (false !== $position) {
            $prefix = substr($pattern, 0, $position);
            $variablePattern = substr($pattern, $position);
        }

        if ($prefix) {
            $this->setStaticPrefix($prefix);
        } else {
            $this->setStaticPrefix('');
        }

        if ($variablePattern) {
            $this->setVariablePattern($variablePattern);
        }

        return $this;
    }

    public function generateRouteName()
    {
        $this->setName('r'.uniqid());
    }
}
