<?php
/**
 * OrderType.php
 *
 * @since 14/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\OrderBundle\Form\Type\OrderType as BaseOrderType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends BaseOrderType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('billingAddress', 'sylius_address')
            ->add('shippingAddress', 'sylius_address')
            ->add('differentBillingAddress', 'enhavo_boolean');
        ;
    }

    public function getName()
    {
        return 'enhavo_shop_order';
    }
}