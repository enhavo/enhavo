<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionActionType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionRuleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('startsAt', DateTimeType::class, [
            'label' => 'sylius.form.promotion.starts_at',
            'required' => false,
        ]);

        $builder->add('endsAt', DateTimeType::class, [
            'label' => 'sylius.form.promotion.ends_at',
            'required' => false,
        ]);

        $builder->add('rules', ListType::class, [
            'label' => 'sylius.form.promotion.rules',
            'button_add_label' => 'sylius.form.promotion.add_rule',
            'entry_type' => PromotionRuleType::class
        ]);

        $builder->add('actions', ListType::class, [
            'label' => 'sylius.form.promotion.actions',
            'button_add_label' => 'sylius.form.promotion.add_action',
            'entry_type' => PromotionActionType::class
        ]);
    }

    public function getParent()
    {
        return \Sylius\Bundle\PromotionBundle\Form\Type\PromotionType::class;
    }
}
