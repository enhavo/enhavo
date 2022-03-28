<?php

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'label.tag',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_tag_index',
            'role' => 'ROLE_ENHAVO_SHOP_TAG_INDEX'
        ]);
    }

    public function getType()
    {
        return 'shop_tag';
    }
}
