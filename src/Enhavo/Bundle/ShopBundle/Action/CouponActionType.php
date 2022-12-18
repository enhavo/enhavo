<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponActionType extends OpenActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        if (!$resource instanceof PromotionInterface) {
            throw new \InvalidArgumentException(sprintf('Resource need to be type of "%s"', PromotionInterface::class));
        }

        $options['route_parameters'] = array_merge($options['route_parameters'], [
            'promotionId' => $resource->getId()
        ]);

        return parent::createViewData($options, $resource);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'featured_play_list',
            'target' => '_view',
            'view_key' => 'coupons',
            'route' => 'sylius_promotion_coupon_index',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'promotion.label.coupons'
        ]);
    }

    public function getType()
    {
        return 'shop_coupon';
    }
}
