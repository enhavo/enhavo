<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmType extends AbstractType
{
    public function __construct(
        private string $dataClass
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_order_confirm';
    }
}
