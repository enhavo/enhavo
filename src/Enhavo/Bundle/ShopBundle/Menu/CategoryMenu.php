<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 15.09.20
 * Time: 17:57
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'label.category',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_category_index',
            'role' => 'ROLE_ENHAVO_SHOP_CATEGORY_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_category';
    }
}
