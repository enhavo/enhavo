<?php

namespace Enhavo\Bundle\RedirectBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class Redirect implements RedirectInterface, ResourceInterface
{
    private ?int $id = null;
    private ?string $from  = null;
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

    public function setRoute(RouteInterface $route = null): void
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
