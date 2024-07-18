<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\FormActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateCouponActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'autorenew',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_promotion_coupon_create_form',
            'label' => 'promotion.coupon.action.generate',
            'data_class' => GenerateCouponActionType::class,
            'open_type' => FormActionType::TYPE_RELOAD,
        ]);
    }

    public static function getParentType(): ?string
    {
        return FormActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_generate_coupon';
    }
}
