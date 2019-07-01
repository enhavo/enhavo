<?php

namespace Enhavo\Bundle\CalendarBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\CalendarBundle\Entity\CalendarBlock'
        ));
    }

    public function getName()
    {
        return 'enhavo_calendar_calendar_block';
    }
}
