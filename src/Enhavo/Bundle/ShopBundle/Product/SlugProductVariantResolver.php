<?php

namespace Enhavo\Bundle\ShopBundle\Product;

use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Symfony\Component\HttpFoundation\Request;

class SlugProductVariantResolver implements ProductVariantResolverInterface
{
    public function __construct(
        private $parameterName = 'variant'
    )
    {
    }

    public function resolve(ProductInterface $product, Request $request): ?ProductVariantInterface
    {
        if ($request->get($this->parameterName)) {
            $slug = $request->get($this->parameterName);
            foreach ($product->getVariants() as $variant) {
                if ($variant->getSlug() === $slug) {
                    return $variant;
                }
            }
        } else {
            return $product->getDefaultVariant();
        }

        return null;
    }
}
