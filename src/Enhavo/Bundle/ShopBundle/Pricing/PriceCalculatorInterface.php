<?php

namespace Enhavo\Bundle\ShopBundle\Pricing;

use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;

interface PriceCalculatorInterface
{
    public function calculate(ProductAccessInterface $product, array $configuration = []): int;

    public function isSupported(ProductAccessInterface $product): bool;
}
