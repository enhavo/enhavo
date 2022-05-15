<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRouteInterface;

final class ResolveNextRouteFactory implements ResolveNextRouteFactoryInterface
{
    public function createNewWithModel($model): ResolveNextRouteInterface
    {
        return new ResolveNextRoute($model);
    }
}
