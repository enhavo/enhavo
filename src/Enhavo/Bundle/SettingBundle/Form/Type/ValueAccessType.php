<?php

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueAccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', $options['form_type'], $options['form_options']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ValueAccessInterface::class,
            'form_options' => []
        ]);

        $resolver->setRequired([
            'form_type'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_setting_value_access';
    }
}
