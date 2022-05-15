<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Payum\Core\Request\GetStatusInterface;

interface GetStatusFactoryInterface
{
    public function createNewWithModel($model): GetStatusInterface;
}
