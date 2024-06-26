<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\OpenActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateVariantActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'autorenew',
            'target' => '_view',
            'route' => 'sylius_product_variant_generate',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'product_variant.action.label.generate'
        ]);
    }
    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_generate_variant';
    }
}
