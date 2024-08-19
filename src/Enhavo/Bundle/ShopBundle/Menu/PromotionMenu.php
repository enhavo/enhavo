<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 14.09.20
 * Time: 16:44
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'add_shopping_cart',
            'label' =>  'product.label.promotion',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_promotion_index',
            'role' => 'ROLE_SYLIUS_PROMOTION_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_promotion';
    }
}
