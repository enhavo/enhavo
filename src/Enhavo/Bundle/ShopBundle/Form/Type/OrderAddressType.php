<?php
/**
 * OrderAddressingType.php
 *
 * @since 15/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\ShopBundle\Order\OrderAddressProvider;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OrderAddressType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var OrderAddressProvider
     */
    private $orderAddressProvider;

    public function __construct($dataClass, OrderAddressProvider $orderAddressProvider)
    {
        $this->dataClass = $dataClass;
        $this->orderAddressProvider = $orderAddressProvider;
     }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $orderAddressProvider = $this->orderAddressProvider;

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (!array_key_exists('differentBillingAddress', $data) || false === $data['differentBillingAddress']) {
                    $data['billingAddress'] = $data['shippingAddress'];
                    $event->setData($data);
                }
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if($data instanceof OrderInterface) {
                    $user = $data->getUser();
                    if($user === null) {
                        $form->add('email', 'email');
                    }
                }
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($orderAddressProvider) {
                $data = $event->getData();
                if($data instanceof OrderInterface) {
                    $orderAddressProvider->provide($data);
                }
            })
            ->add('shippingAddress', 'sylius_address', [
                'shippable' => true,
                'validation_groups' => ['shipping']
            ])
            ->add('billingAddress', 'sylius_address')
            ->add('differentBillingAddress', 'checkbox', [
                'required' => false,
                'label' => 'checkout.addressing.form.label.different_billing_address',
                'translation_domain' => 'EnhavoShopBundle',
                'attr' => [
                    'data-checkout-addressing-different-billing-address' => ''
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'customer' => null,
            'validation_groups' => ['shipping']
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_order_address';
    }
}