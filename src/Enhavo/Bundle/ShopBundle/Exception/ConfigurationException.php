<?php

namespace Enhavo\Bundle\ShopBundle\Exception;

class ConfigurationException extends \Exception
{
    public static function productVariantProxyFactory($serviceName)
    {
        return new self(sprintf('The value configured under enhavo_shop.product.variant_proxy.factory should be service, but no service found with name "%s"', $serviceName));
    }
}
