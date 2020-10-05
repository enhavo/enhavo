<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateVariantActionType extends OpenActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'autorenew',
            'target' => '_view',
            'route' => 'sylius_product_variant_generate',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'product_variant.action.label.generate'
        ]);
    }

    public function getType()
    {
        return 'shop_generate_variant';
    }
}
