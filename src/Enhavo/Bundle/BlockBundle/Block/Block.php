<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\FormBundle\DynamicForm\AbstractBlock;

class Block extends AbstractBlock
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

    public function getFormTemplate()
    {
        return $this->getConfiguration()->getFormTemplate($this->options);
    }

    public function getGroups()
    {
        return $this->getConfiguration()->getGroups($this->options);
    }
}