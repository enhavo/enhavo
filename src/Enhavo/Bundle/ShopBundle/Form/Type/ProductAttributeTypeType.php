<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeTypeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'product_attribute.action.label.text' => 'text',
                'product_attribute.action.label.textarea' => 'textarea',
                'product_attribute.action.label.checkbox' => 'checkbox',
                'product_attribute.action.label.integer' => 'integer',
                'product_attribute.action.label.percent' => 'percent',
                'product_attribute.action.label.datetime' => 'datetime',
                'product_attribute.action.label.date' => 'date',
                'product_attribute.action.label.select' => 'select',
            ],
            'translation_domain' => 'EnhavoShopBundle'
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
