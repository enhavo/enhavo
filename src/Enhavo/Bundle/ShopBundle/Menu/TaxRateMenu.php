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

class TaxRateMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'work',
            'label' =>  'tax.label.tax_rate',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_tax_rate_index',
            'role' => 'ROLE_SYLIUS_TAX_RATE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_tax_rate';
    }
}
