<?php

namespace Enhavo\Bundle\BlockBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\SliderBundle\Entity\SliderBlock'
        ));
    }

    public function getName()
    {
        return 'enhavo_slider_slider_block';
    }
}
