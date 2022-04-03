<?php

namespace Enhavo\Bundle\ShopBundle\Model;

interface ProductVariantProxyInterface extends ProductAccessInterface
{
    public function getProductVariant(): ProductVariantInterface;
}
