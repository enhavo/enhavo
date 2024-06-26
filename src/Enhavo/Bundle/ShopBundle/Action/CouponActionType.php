<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class CouponActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        if (!$resource instanceof PromotionInterface) {
            throw new \InvalidArgumentException(sprintf('Resource need to be type of "%s"', PromotionInterface::class));
        }

        $data->set('url', $this->router->generate($options['route'], array_merge($options['route_parameters'], [
            'promotionId' => $resource->getId()
        ])));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'featured_play_list',
            'target' => '_view',
            'view_key' => 'coupons',
            'route' => 'sylius_promotion_coupon_index',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'promotion.label.coupons'
        ]);
    }

    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_coupon';
    }
}
