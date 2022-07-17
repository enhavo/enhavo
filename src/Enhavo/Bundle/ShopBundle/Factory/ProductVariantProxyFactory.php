<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Enhavo\Bundle\ShopBundle\Product\ProductVariantProxyEnhancerInterface;
use Laminas\Stdlib\PriorityQueue;

class ProductVariantProxyFactory implements ProductVariantProxyFactoryInterface
{
    /** @var PriorityQueue<ProductVariantProxyEnhancerInterface>|ProductVariantProxyEnhancerInterface[]  */
    private PriorityQueue $variantProxyEnhancer;

    /** @var array|ProductVariantInterface[]  */
    private array $variantProxyCache = [];

    public function __construct(
        private string $class
    ) {
        $this->variantProxyEnhancer = new PriorityQueue();
    }

    public function addVariantProxyEnhancer(ProductVariantProxyEnhancerInterface $enhancer, $priority = 10)
    {
        $this->variantProxyEnhancer->insert($enhancer, $priority);
    }

    public function createNew(ProductVariantInterface $productVariant): ProductVariantProxyInterface
    {
        $objectHash = spl_object_hash($productVariant);
        if (isset($this->variantProxyCache[$objectHash])) {
            return $this->variantProxyCache[$objectHash];
        }

        $proxy = $this->createInstance($productVariant);

        foreach ($this->variantProxyEnhancer as $enhancer) {
            $enhancer->enhance($proxy);
        }

        $this->variantProxyCache[$objectHash] = $proxy;
        return $proxy;
    }

    protected function createInstance(ProductVariantInterface $productVariant)
    {
        return new $this->class($productVariant);
    }
}
