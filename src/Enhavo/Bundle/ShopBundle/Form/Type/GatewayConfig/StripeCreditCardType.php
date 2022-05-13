<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\GatewayConfig;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StripeCreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('test', TextType::class, [
            'label' => 'foobar11'
        ]);
    }
}
