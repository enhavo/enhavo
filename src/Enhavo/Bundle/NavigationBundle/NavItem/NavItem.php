<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem;

use Enhavo\Component\Type\AbstractContainerType;

class NavItem extends AbstractContainerType
{
    /** @var NavItemTypeInterface */
    protected $type;

    public function getModel()
    {
        return $this->type->getModel($this->options);
    }

    public function getForm()
    {
        return $this->type->getForm($this->options);
    }

    public function getFactory()
    {
        return $this->type->getFactory($this->options);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate($this->options);
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
