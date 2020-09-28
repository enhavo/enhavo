<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantActionType extends OpenActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        if (!$resource instanceof ProductInterface) {
            throw new \InvalidArgumentException(sprintf('Resource need to be type of "%s"', ProductInterface::class));
        }

        $options['route_parameters'] = array_merge($options['route_parameters'], [
            'productId' => $resource->getId()
        ]);

        return parent::createViewData($options, $resource);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'texture',
            'target' => '_view',
            'view_key' => 'variants',
            'route' => 'sylius_product_variant_index',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'product.label.variants'
        ]);
    }

    public function getType()
    {
        return 'shop_variant';
    }
}
