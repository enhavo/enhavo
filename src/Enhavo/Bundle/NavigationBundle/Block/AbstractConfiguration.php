<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 19:02
 */

namespace Enhavo\Bundle\NavigationBundle\Block;

use Enhavo\Bundle\FormBundle\DynamicForm\ConfigurationInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
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

    public function getContentModel($options)
    {
        return $options['content_model'];
    }

    public function getContentFactory($options)
    {
        return $options['content_factory'];
    }

    public function getContentForm($options)
    {
        return $options['content_form'];
    }

    public function getConfigurationForm($options)
    {
        return $options['configuration_form'];
    }

    public function getConfigurationFactory($options)
    {
        return $options['configuration_factory'];
    }

    public function getRenderTemplate($options)
    {
        return $options['render_template'];
    }

    public function getGroups($options)
    {
        return $options['groups'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'options' => [],
            'parent' => null,
            'translationDomain' => null,
            'factory' => $this->container->getParameter('enhavo_navigation.default.factory'),
            'model' => $this->container->getParameter('enhavo_navigation.default.model'),
            'template' => $this->container->getParameter('enhavo_navigation.default.template'),
            'form' => $this->container->getParameter('enhavo_navigation.default.form'),
            'repository' => $this->container->getParameter('enhavo_navigation.default.repository'),
            'content_model' => null,
            'content_factory' => null,
            'content_form' => null,
            'configuration_form' => null,
            'configuration_factory' => null,
            'render_template' => null,
            'groups' => ['default'],
        ]);

        $resolver->setRequired([
            'label',
            'type',
        ]);
    }
}