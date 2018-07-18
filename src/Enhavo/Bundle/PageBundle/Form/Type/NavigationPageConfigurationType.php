<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.05.18
 * Time: 15:17
 */

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Enhavo\Bundle\NavigationBundle\Form\Type\TargetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NavigationPageConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('anker', TextType::class, [

        ]);

        $builder->add('target', TargetType::class, []);
    }

    public function getName()
    {
        return 'enhavo_page_navigation_page_configuration';
    }
}