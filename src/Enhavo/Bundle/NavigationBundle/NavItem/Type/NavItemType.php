<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-27
 * Time: 08:33
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem\Type;

use Enhavo\Bundle\NavigationBundle\NavItem\NavItemTypeInterface;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavItemType implements NavItemTypeInterface
{
    public function getModel($options)
    {
        return $options['model'];
    }

    public function getForm($options)
    {
        return $options['form'];
    }

    public function getLabel(array $options)
    {
        return $options['label'];
    }

    public function getTranslationDomain(array $options)
    {
        return $options['translation_domain'];
    }

    public function getFactory($options)
    {
        return $options['factory'];
    }

    public function getTemplate($options)
    {
        return $options['template'];
    }

    public function render($options)
    {
        return '';
    }

    public static function getName(): ?string
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public function setParent(TypeInterface $parent)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'template' => null,
        ]);

        $resolver->setRequired([
            'label', 'model', 'factory',
        ]);
    }
}
