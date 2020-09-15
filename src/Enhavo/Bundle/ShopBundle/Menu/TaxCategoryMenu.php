<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 15.09.20
 * Time: 17:58
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxCategoryMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'work',
            'label' =>  'tax.label.tax_category',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_tax_category_index',
            'role' => 'ROLE_SYLIUS_TAX_CATEGORY_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_tax_category';
    }
}
