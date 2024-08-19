<?php
/**
 * ProductMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'card_giftcard',
            'label' =>  'voucher.label.voucher',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_voucher_index',
            'role' => 'ROLE_ENHAVO_SHOP_VOUCHER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_voucher';
    }
}
