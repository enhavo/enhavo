<?php

/**
 * ResourceType.php
 *
 * @since 12/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SerializerBundle\Tests\Mock;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('resources', 'collection', [
            'type' => new ResourceType()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'Enhavo\Bundle\SerializerBundle\Tests\Mock\Resource',
        ));
    }

    public function getName()
    {
        return 'resource';
    }
}