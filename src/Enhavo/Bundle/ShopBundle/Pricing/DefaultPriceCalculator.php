<?php

namespace Enhavo\Bundle\ShopBundle\Pricing;

use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $configuration = $this->configure($configuration);
        $price = $this->getPrice($product, $configuration);

        if ($taxRate === null || !$taxRate->isIncludedInPrice()) {
            return $price;
        }

        return $price - $this->calculator->calculate($price, $taxRate);
    }

    public function calculateTax(ProductAccessInterface $product, array $configuration = []): int
    {
        /** ProductVariantProxyInterface $product */
        $taxRate = $this->taxRateResolver->resolve($product);
        if ($taxRate === null) {
            return 0;
        }

        $configuration = $this->configure($configuration);
        $price = $this->getPrice($product, $configuration);

        return $this->calculator->calculate($price, $taxRate);
    }

    public function isSupported(ProductAccessInterface $product): bool
    {
        return $product instanceof ProductVariantProxyInterface;
    }

    private function getPrice(ProductAccessInterface $product, array $configuration)
    {
        return PropertyAccess::createPropertyAccessor()->getValue($product, $configuration['property']);
    }

    private function configure($configuration)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults([
            'property' => 'price'
        ]);

        return $optionResolver->resolve($configuration);
    }
}
