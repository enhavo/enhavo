<?php
/**
 * Created by PhpStorm.
 * User: schaetzle
 * Date: 25.10.18
 * Time: 15:25
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CssClassType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'CSS Class(es)'
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'enhavo_navigation_css_class';
    }
}