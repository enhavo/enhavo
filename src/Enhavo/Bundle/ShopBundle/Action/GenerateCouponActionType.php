<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\FormActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateCouponActionType extends FormActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'autorenew',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_promotion_coupon_create_form',
            'label' => 'promotion.coupon.action.generate',
            'data_class' => GenerateCouponActionType::class,
            'open_type' => FormActionType::TYPE_RELOAD,
        ]);
    }

    public function getType()
    {
        return 'shop_generate_coupon';
    }
}
