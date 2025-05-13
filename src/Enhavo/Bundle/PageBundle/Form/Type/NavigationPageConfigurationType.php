<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        $builder->add('anker', TextType::class, []);
        $builder->add('target', TargetType::class, []);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_page_navigation_page_configuration';
    }
}
