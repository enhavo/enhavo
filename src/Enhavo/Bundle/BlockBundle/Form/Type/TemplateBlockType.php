<?php

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\TemplateBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TemplateBlock::class
        ));
    }
}
