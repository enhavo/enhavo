<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Sylius\Bundle\PayumBundle\Request\ResolveNextRouteInterface;

interface ResolveNextRouteFactoryInterface
{
    public function createNewWithModel($model): ResolveNextRouteInterface;
}
