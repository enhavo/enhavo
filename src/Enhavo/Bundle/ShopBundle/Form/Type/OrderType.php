<?php
/**
 * OrderType.php
 *
 * @since 14/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('state', 'choice', [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                OrderInterface::STATE_CONFIRMED => 'order.form.label.state.confirmed',
                OrderInterface::STATE_CANCELLED => 'order.form.label.state.cancelled',
                OrderInterface::STATE_RETURNED => 'order.form.label.state.returned',
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.state.label'
        ]);

        $builder->add('paymentState', 'choice', [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                PaymentInterface::STATE_PENDING => 'order.form.label.payment.pending',
                PaymentInterface::STATE_COMPLETED => 'order.form.label.payment.completed',
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.payment.label'
        ]);

        $builder->add('shippingState', 'choice', [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                ShipmentInterface::STATE_PENDING => 'order.form.label.shipping.pending',
                ShipmentInterface::STATE_READY => 'order.form.label.shipping.ready',
                ShipmentInterface::STATE_SHIPPED => 'order.form.label.shipping.shipped'
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.shipping.label'
        ]);

        $builder->add('items', 'enhavo_list', [
            'type' => 'enhavo_shop_order_item',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);

        $builder->add('billingAddress', 'sylius_address');
        $builder->add('shippingAddress', 'sylius_address');
        $builder->add('differentBillingAddress', 'enhavo_boolean');

        $builder->add('payment', 'enhavo_shop_payment');
    }

    public function getName()
    {
        return 'enhavo_shop_order';
    }
}