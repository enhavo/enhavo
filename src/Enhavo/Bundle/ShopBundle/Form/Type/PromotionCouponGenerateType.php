<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponGeneratorInstructionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PromotionCouponGenerateType extends AbstractType
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
        return PromotionCouponGeneratorInstructionType::class;
    }
}
