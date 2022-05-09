<?php
/**
 * ShipmentType.php
 *
 * @since 03/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ShipmentType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $shipment = $event->getData();

                $form->add('method', ShippingMethodChoiceType::class, [
                    'required' => true,
                    'label' => 'sylius.form.checkout.shipping_method',
                    'subject' => $shipment,
                    'expanded' => true,
                ]);
            });
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_shipment';
    }
}
