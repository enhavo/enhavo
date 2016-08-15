<?php
/**
 * OrderAddressingType.php
 *
 * @since 15/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class OrderAddressType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (!array_key_exists('differentBillingAddress', $data) || false === $data['differentBillingAddress']) {
                    $data['billingAddress'] = $data['shippingAddress'];
                    $event->setData($data);
                }
            })
            ->add('shippingAddress', 'sylius_address', ['shippable' => true])
            ->add('billingAddress', 'sylius_address')
            ->add('differentBillingAddress', 'checkbox', [
                'required' => false,
                'label' => 'sylius.form.checkout.addressing.different_billing_address',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'customer' => null
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_order_address';
    }
}