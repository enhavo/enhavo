<?php

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'label.tag',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_tag_index',
            'role' => 'ROLE_ENHAVO_SHOP_TAG_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_tag';
    }
}
