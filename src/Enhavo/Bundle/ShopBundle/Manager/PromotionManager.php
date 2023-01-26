<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Sylius\Component\Promotion\Model\PromotionInterface;

class PromotionManager
{
    public function __construct()
    {}

    public function update(PromotionInterface $promotion)
    {
        if ($promotion->getCode() === null) {
            $promotion->setCode($this->generateCode($promotion->getName()));
        }
    }

    private function generateCode($name): string
    {
        return substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
    }
}
