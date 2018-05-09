<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'model' => null,
            'form' => null,
            'repository' => null,
            'label' => null,
            'translationDomain' => null,
            'type' => null,
            'parent' => null,
            'factory' => null,
            'template' => 'EnhavoAppBundle:Menu:base.html.twig',
            'options' => []
        ]);
    }

    public function getType()
    {
        return 'base';
    }
}