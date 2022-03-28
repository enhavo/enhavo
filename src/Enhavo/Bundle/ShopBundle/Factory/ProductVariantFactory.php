<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class ProductVariantFactory extends Factory implements ProductVariantFactoryInterface
{
    public function __construct(
        string $class,
    ) {
        parent::__construct($class);
    }

    /**
     * {@inheritdoc}
     */
    public function createForProduct(ProductInterface $product): ProductVariantInterface
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->createNew();
        $variant->setProduct($product);

        return $variant;
    }
}
