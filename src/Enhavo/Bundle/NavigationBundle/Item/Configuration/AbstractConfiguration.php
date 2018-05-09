<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 19:02
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Enhavo\Bundle\AppBundle\DynamicForm\ConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfiguration implements ConfigurationInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'options' => [],
            'parent' => null,
        ]);

        $resolver->setRequired([
            'model',
            'form',
            'repository',
            'label',
            'translationDomain',
            'type',
            'parent',
            'factory',
            'template',
        ]);
    }
}