<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class OrderItemFactory extends Factory
{
    public function createWithProductVariant(ProductVariantInterface $productVariant): OrderItemInterface
    {
        /** @var OrderItemInterface $item */
        $item = $this->createNew();
        $item->setProduct($productVariant);
        return $item;
    }
}
