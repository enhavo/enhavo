<?php
/**
 * OrderAddressingType.php
 *
 * @since 15/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Config\Condition;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Sylius\Component\Addressing\Comparator\AddressComparatorInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressSubjectType extends AbstractType
{
    public function __construct(
        private AddressComparatorInterface $addressComparator,
        private TranslatorInterface $translator
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $this->translator;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) use ($options, $translator): void {
                $form = $event->getForm();
                $form->add('billingAddress', AddressType::class, [
                    'label' => $translator->trans('address.label.billingAddress', [], 'EnhavoShopBundle')
                ]);

                if ($options['shippable']) {
                    $condition = new Condition();
                    $form
                        ->add('shippingAddress', AddressType::class, [
                            'condition_observer' => $condition->createObserver(BooleanType::VALUE_FALSE),
                            'label' => $translator->trans('address.label.shippingAddress', [], 'EnhavoShopBundle'),
                        ])
                        ->add('sameAddress', BooleanType::class, [
                            'condition' => $condition,
                            'mapped' => false,
                            'label' => $translator->trans('address.label.sameAddress', [], 'EnhavoShopBundle'),
                        ])
                    ;
                }
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options): void {
                if (!$options['shippable']) {
                    return;
                }

                $form = $event->getForm();

                /** @var AddressSubjectInterface $subject */
                $subject = $options['subject'] ?: $event->getData();
                $sameAddress = $subject && $this->areAddressesSame($subject->getBillingAddress(), $subject->getShippingAddress());

                $form->get('sameAddress')->setData($sameAddress);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options): void {
                if (!$options['shippable']) {
                    return;
                }

                $data = $event->getData();
                $sameAddress = $data['sameAddress'] ?? false;

                if (isset($data['billingAddress']) && $sameAddress) {
                    $data['shippingAddress'] = $data['billingAddress'];
                }

                $event->setData($data);
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
        $resolver->setDefaults([
            'shippable' => true,
            'subject' => null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_address_subject';
    }
}
