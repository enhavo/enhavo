<?php
/**
 * OrderAddressingType.php
 *
 * @since 15/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Addressing\Comparator\AddressComparatorInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

class OrderAddressType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private ?AddressComparatorInterface $addressComparator,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event): void {
                $form = $event->getForm();

                Assert::isInstanceOf($event->getData(), OrderInterface::class);

                /** @var OrderInterface $order */
                $order = $event->getData();

                $form->add('billingAddress', AddressType::class);

                if ($order->isShippable()) {
                    $form
                        ->add('shippingAddress', AddressType::class)
                        ->add('sameAddress', BooleanType::class, [
                            'mapped' => false,
                            'required' => false,
                            'label' => 'sylius.form.checkout.addressing.different_billing_address',
                        ])
                    ;
                }
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
                $form = $event->getForm();

                Assert::isInstanceOf($event->getData(), OrderInterface::class);

                /** @var OrderInterface $order */
                $order = $event->getData();
                $sameAddress = $this->areAddressesSame($order->getBillingAddress(), $order->getShippingAddress());

                $form->get('sameAddress')->setData($sameAddress);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $orderData = $event->getData();

                $sameAddress = $orderData['sameAddress'] ?? false;

                if (isset($orderData['billingAddress']) && $sameAddress) {
                    $orderData['billingAddress'] = $orderData['shippingAddress'];
                }

                $event->setData($orderData);
            })
        ;
    }

    private function areAddressesSame(?AddressInterface $firstAddress, ?AddressInterface $secondAddress): bool
    {
        if (null === $firstAddress || null === $secondAddress) {
            return false;
        }

        return $this->addressComparator->equal($firstAddress, $secondAddress);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_order_address';
    }
}
