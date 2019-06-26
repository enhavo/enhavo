<?php

namespace Enhavo\Bundle\TemplateBundle\Form\Type;


use Enhavo\Bundle\TemplateBundle\Entity\ResourceBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ResourceBlock::class
        ));
    }

    public function getName()
    {
        return 'enhavo_template_resource';
    }
}
