<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\NavigationBundle\Item;

use Enhavo\Bundle\AppBundle\DynamicForm\AbstractItem;

class Item extends AbstractItem
{
    /**
     * @return AbstractConfiguration
     */
    private function getConfiguration()
    {
        /** @var AbstractConfiguration $configuration */
        $configuration = $this->configuration;
        return $configuration;
    }

    public function getModel()
    {
        return $this->getConfiguration()->getModel($this->options);
    }

    public function getForm()
    {
        return $this->getConfiguration()->getForm($this->options);
    }

    public function getParent()
    {
        return $this->getConfiguration()->getParent($this->options);
    }

    public function getFactory()
    {
        return $this->getConfiguration()->getFactory($this->options);
    }

    public function getTemplate()
    {
        return $this->getConfiguration()->getTemplate($this->options);
    }

    public function getContentModel()
    {
        return $this->getConfiguration()->getContentModel($this->options);
    }

    public function getContentFactory()
    {
        return $this->getConfiguration()->getContentFactory($this->options);
    }

    public function getContentForm()
    {
        return $this->getConfiguration()->getContentForm($this->options);
    }

    public function getConfigurationForm()
    {
        return $this->getConfiguration()->getConfigurationForm($this->options);
    }

    public function getConfigurationFactory()
    {
        return $this->getConfiguration()->getConfigurationFactory($this->options);
    }

    public function getGroups()
    {
        return $this->getConfiguration()->getGroups($this->options);
    }

    public function getRenderTemplate()
    {
        return $this->getConfiguration()->getRenderTemplate($this->options);
    }
}