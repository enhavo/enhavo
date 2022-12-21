<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Promotion;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class NthOrderConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nth', IntegerType::class, [
                'label' => 'sylius.form.promotion_rule.nth_order_configuration.nth',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                ],
            ])
            ->add('from', DateType::class, [
                'label' => 'from',
            ])
            ->add('to', DateType::class, [
                'label' => 'to',
            ])
        ;
    }
}
