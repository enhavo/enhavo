<?php
/**
 * UserAddressType.php
 *
 * @since 13/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserAddressType extends AbstractType
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
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (!array_key_exists('differentBillingAddress', $data) || false === $data['differentBillingAddress'] || 'false' == $data['differentBillingAddress']) {
                $data['billingAddress'] = $data['shippingAddress'];
                $event->setData($data);
            }
        });
        $builder->add('billingAddress', 'sylius_address');
        $builder->add('shippingAddress', 'sylius_address');
        $builder->add('differentBillingAddress', 'enhavo_boolean');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_user_address';
    }
}