<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PromotionCouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('expiresAt', DateType::class, [
            'required' => false,
            'label' => 'sylius.form.promotion_coupon_generator_instruction.expires_at',
        ]);
    }

    public function getParent()
    {
        return \Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponType::class;
    }
}
