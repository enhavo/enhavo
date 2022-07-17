<?php

namespace Enhavo\Bundle\ShopBundle\Product;

use Enhavo\Bundle\ShopBundle\Manager\PricingManager;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxy;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

class PriceEnhancer implements ProductVariantProxyEnhancerInterface
{
    public function __construct(
        private PricingManager $priceManager
    )
    {
    }

    public function enhance(ProductVariantProxyInterface $productVariantProxy)
    {
        if ($productVariantProxy instanceof ProductVariantProxy) {
            $price = $this->priceManager->calculatePrice($productVariantProxy);
            $tax = $this->priceManager->calculateTax($productVariantProxy);

            $productVariantProxy->setGrossPrice($price + $tax);
            $productVariantProxy->setNetPrice($price);
        }
    }
}
