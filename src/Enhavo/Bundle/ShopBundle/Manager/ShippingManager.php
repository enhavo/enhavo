<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;

class ShippingManager
{
    public function updateShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        if ($shippingMethod->getPosition() === null) {
            $shippingMethod->setPosition(0);
        }

        if ($shippingMethod->getCode() === null) {
            $shippingMethod->setCode($this->generateCode($shippingMethod));
        }
    }

    private function generateCode(ShippingMethodInterface $shippingMethod)
    {
        $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($shippingMethod->getName());
        return $code;
    }
}
