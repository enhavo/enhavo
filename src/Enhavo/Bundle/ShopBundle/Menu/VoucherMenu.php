<?php
/**
 * ProductMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'gift',
            'label' =>  'voucher.label.voucher',
            'translationDomain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_voucher_index',
            'role' => 'ROLE_ENHAVO_SHOP_VOUCHER_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_voucher';
    }
}