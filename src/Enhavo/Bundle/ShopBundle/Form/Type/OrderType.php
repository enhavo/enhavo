<?php
/**
 * OrderType.php
 *
 * @since 14/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;


use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Enhavo\Bundle\ShopBundle\State\OrderShippingStates;
use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('state', ChoiceType::class, [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                OrderInterface::STATE_NEW => 'order.form.label.state.new',
                OrderInterface::STATE_CANCELLED => 'order.form.label.state.cancelled',
                OrderInterface::STATE_FULFILLED => 'order.form.label.state.fulfilled',
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.state.label'
        ]);

        $builder->add('paymentState', ChoiceType::class, [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                OrderPaymentStates::STATE_AWAITING_PAYMENT => 'order.form.label.payment.pending',
                OrderPaymentStates::STATE_PAID => 'order.form.label.payment.pending',
                OrderPaymentStates::STATE_PARTIALLY_PAID => 'order.form.label.payment.pending',
                OrderPaymentStates::STATE_PARTIALLY_REFUNDED => 'order.form.label.payment.pending',
                OrderPaymentStates::STATE_REFUNDED => 'order.form.label.payment.completed',
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.payment.label'
        ]);

        $builder->add('shippingState', ChoiceType::class, [
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                OrderShippingStates::STATE_PARTIALLY_SHIPPED => 'order.form.label.shipping.pending',
                OrderShippingStates::STATE_READY => 'order.form.label.shipping.shipped',
                OrderShippingStates::STATE_SHIPPED => 'order.form.label.shipping.ready',
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.shipping.label'
        ]);

        $builder->add('billingAddress', AddressType::class);
        $builder->add('shippingAddress', AddressType::class);
    }

    public function getBlockPrefix(): string
    {
        return 'enhavo_shop_order';
    }
}
