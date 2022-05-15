<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Request;

use Payum\Core\Request\Generic;

class ResolveNextRoute extends Generic implements ResolveNextRouteInterface
{
    private ?string $routeName = null;

    /** @var array */
    private $routeParameters = [];

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(array $parameters): void
    {
        $this->routeParameters = $parameters;
    }
}
