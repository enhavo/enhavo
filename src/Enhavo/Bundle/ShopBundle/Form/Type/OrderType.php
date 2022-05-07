<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\ShopBundle\Entity\Order;
use Enhavo\Bundle\ShopBundle\Form\Type\Order\PaymentStateType;
use Enhavo\Bundle\ShopBundle\Form\Type\Order\ShippingStateType;
use Enhavo\Bundle\ShopBundle\Form\Type\Order\StateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', AddressSubjectType::class, [
            'data_class' => Order::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'enhavo_shop_order';
    }
}
