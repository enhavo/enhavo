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

class ProductAssociationTypeMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'work',
            'label' =>  'product.label.product_association_type',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_product_association_type_index',
            'role' => 'ROLE_SYLIUS_PRODUCT_ASSOCIATION_TYPE_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_product_association_type';
    }
}
