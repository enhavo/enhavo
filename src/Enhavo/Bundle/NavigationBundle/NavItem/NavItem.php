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

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property NavItemTypeInterface $type
 */
class NavItem extends AbstractContainerType
{
    public function getModel()
    {
        return $this->type->getModel($this->options);
    }

    public function getForm()
    {
        return $this->type->getForm($this->options);
    }

    public function getFormOptions()
    {
        return $this->type->getFormOptions($this->options);
    }

    public function getFactory()
    {
        return $this->type->getFactory($this->options);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate($this->options);
    }

    public function getComponent()
    {
        return $this->type->getComponent($this->options);
    }

    public function getLabel()
    {
        return $this->type->getLabel($this->options);
    }

    public function getTranslationDomain()
    {
        return $this->type->getTranslationDomain($this->options);
    }
}
