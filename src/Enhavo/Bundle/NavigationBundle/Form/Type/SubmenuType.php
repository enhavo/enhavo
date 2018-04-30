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

class SubmenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'children' => true,
            'label' => 'Submenu'
        ]);
    }

    public function getParent()
    {
        return NodeType::class;
    }

    public function getName()
    {
        return 'enhavo_navigation_submenu';
    }
}