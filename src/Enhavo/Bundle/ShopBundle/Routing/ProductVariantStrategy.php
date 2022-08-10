<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:27
 */

namespace Enhavo\Bundle\ShopBundle\Routing;

use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductVariantStrategy extends AbstractStrategy
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $productVariant = null;
        if ($resource instanceof ProductVariantProxy) {
            $productVariant = $resource->getProductVariant();
        } elseif ($resource instanceof ProductVariant) {
            $productVariant = $resource;
        } else {
            throw new UrlResolverException(sprintf('Resource type is not supported "%s" given', get_class($resource)));
        }

        if (!$productVariant->isDefault()) {
            $parameters[$options['route_parameter_name']] = $this->getProperty($resource, $options['property']);
        }

        return $this->getRouter()->generate($productVariant->getProduct()->getRoute()->getName(), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'product_variant';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'property' => 'slug',
            'route_parameter_name' => 'variant'
        ]);
    }
}
