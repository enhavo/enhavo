<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 17:55
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('target', TargetType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getName()
    {
        return 'enhavo_navigation_link_configuration';
    }
}