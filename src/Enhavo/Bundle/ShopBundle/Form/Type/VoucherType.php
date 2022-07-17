<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 10.01.17
 * Time: 14:41
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VoucherType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class, [
            'label' => 'voucher.label.code',
            'translation_domain' => 'EnhavoShopBundle'
        ]);

        $builder->add('amount', CurrencyType::class, [
            'label' => 'voucher.label.amount',
            'translation_domain' => 'EnhavoShopBundle'
        ]);

        $builder->add('enabled', BooleanType::class, [
            'label' => 'voucher.label.enabled',
            'translation_domain' => 'EnhavoShopBundle'
        ]);

        $builder->add('partialRedeemable', BooleanType::class, [
            'label' => 'voucher.label.partialRedeemable',
            'translation_domain' => 'EnhavoShopBundle'
        ]);

        $builder->add('expiredAt', DateType::class, [
            'label' => 'voucher.label.expiredAt',
            'translation_domain' => 'EnhavoShopBundle'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_voucher';
    }
}
