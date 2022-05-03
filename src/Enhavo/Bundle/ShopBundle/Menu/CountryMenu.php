<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 15.09.20
 * Time: 17:57
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'language',
            'label' =>  'country.label.country',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_country_index',
            'role' => 'ROLE_SYLIUS_COUNTRY_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_country';
    }
}
