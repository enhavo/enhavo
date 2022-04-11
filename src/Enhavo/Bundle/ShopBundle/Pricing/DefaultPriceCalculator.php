<?php

namespace Enhavo\Bundle\ShopBundle\Pricing;

use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;

class DefaultPriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private CalculatorInterface $calculator,
        private TaxRateResolverInterface $taxRateResolver,
    ) {}

    public function calculate(ProductAccessInterface $product, array $configuration = []): int
    {
        /** ProductVariantProxyInterface $product */
        $taxRate = $this->taxRateResolver->resolve($product);

        if ($taxRate === null || !$taxRate->isIncludedInPrice()) {
            return $product->getPrice();
        }

        return $product->getPrice() - $this->calculator->calculate($product->getPrice(), $taxRate);
    }

    public function isSupported(ProductAccessInterface $product): bool
    {
        return $product instanceof ProductVariantProxyInterface;
    }
}
