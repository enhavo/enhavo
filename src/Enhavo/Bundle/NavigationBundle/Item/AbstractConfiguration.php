<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 19:02
 */

namespace Enhavo\Bundle\NavigationBundle\Item;

use Enhavo\Bundle\AppBundle\DynamicForm\ConfigurationInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Factory\Factory;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfiguration extends AbstractType implements ConfigurationInterface
{
    public function getModel($options)
    {
        return $options['model'];
    }

    public function getForm($options)
    {
        return $options['form'];
    }

    public function getRepository($options)
    {
        return $options['repository'];
    }

    public function getLabel($options)
    {
        return $options['label'];
    }

    public function getTranslationDomain($options)
    {
        return $options['translationDomain'];
    }

    public function getParent($options)
    {
        return $options['parent'];
    }

    public function getFactory($options)
    {
        return $options['factory'];
    }

    public function getTemplate($options)
    {
        return $options['template'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'options' => [],
            'parent' => null,
            'translationDomain' => null,
            'factory' => Factory::class,
            'model' => Node::class
        ]);

        $resolver->setRequired([
            'form',
            'label',
            'type',
            'parent',
            'template',
        ]);
    }
}