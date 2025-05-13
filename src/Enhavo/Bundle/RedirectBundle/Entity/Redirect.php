<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RedirectBundle\Entity;

use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

class Redirect implements RedirectInterface
{
    private ?int $id = null;
    private ?string $from = null;
    private ?string $to = null;
    private ?int $code = null;
    private ?Route $route = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFrom(?string $value): void
    {
        $this->from = $value;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setTo(?string $value): void
    {
        $this->to = $value;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setRoute(?RouteInterface $route = null): void
    {
        $this->route = $route;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): void
    {
        $this->code = $code;
    }
}
