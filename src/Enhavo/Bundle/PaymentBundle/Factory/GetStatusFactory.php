<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Payum\Core\Request\GetStatusInterface;
use Sylius\Bundle\PayumBundle\Request\GetStatus;

final class GetStatusFactory implements GetStatusFactoryInterface
{
    public function createNewWithModel($model): GetStatusInterface
    {
        return new GetStatus($model);
    }
}
