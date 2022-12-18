<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 14.09.20
 * Time: 16:44
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'add_shopping_cart',
            'label' =>  'product.label.promotion',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_promotion_index',
            'role' => 'ROLE_SYLIUS_PROMOTION_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_promotion';
    }
}
