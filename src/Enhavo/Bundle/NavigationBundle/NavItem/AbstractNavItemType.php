<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem;

use Enhavo\Bundle\NavigationBundle\NavItem\Type\NavItemType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractNavItemType extends AbstractType implements NavItemTypeInterface
{
    /** @var NavItemTypeInterface */
    protected $parent;

    public function getModel($options)
    {
        return $this->parent->getModel($options);
    }

    public function getForm($options)
    {
        return $this->parent->getForm($options);
    }

    public function getFormOptions($options)
    {
        return $this->parent->getFormOptions($options);
    }

    public function getLabel(array $options)
    {
        return $this->parent->getLabel($options);
    }

    public function getTranslationDomain(array $options)
    {
        return $this->parent->getTranslationDomain($options);
    }

    public function getFactory($options)
    {
        return $this->parent->getFactory($options);
    }

    public function getTemplate($options)
    {
        return $this->parent->getTemplate($options);
    }

    public function getComponent($options)
    {
        return $this->parent->getComponent($options);
    }

    public function render($options)
    {
        return $this->parent->render($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public static function getName(): ?string
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return NavItemType::class;
    }
}
