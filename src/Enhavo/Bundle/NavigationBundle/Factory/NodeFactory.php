<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.04.18
 * Time: 13:47
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

class NodeFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var FactoryInterface
     */
    private $contentFactory;

    /**
     * @var FactoryInterface
     */
    private $configurationFactory;

    /**
     * @var string
     */
    private $name;

    public function __construct($class, $name)
    {
        $this->class = $class;
        $this->name = $name;
    }

    public function createNew()
    {
        $class = $this->class;
        /** @var NodeInterface $node */
        $node = new $class();

        if($this->contentFactory) {
            $content = $this->contentFactory->createNew();
            $node->setContent($content);
        }

        if($this->configurationFactory) {
            $configuration = $this->configurationFactory->createNew();
            $node->setConfiguration($configuration);
        }

        $node->setType($this->name);

        return $node;
    }

    /**
     * @return FactoryInterface
     */
    public function getContentFactory()
    {
        return $this->contentFactory;
    }

    /**
     * @param FactoryInterface $contentFactory
     */
    public function setContentFactory($contentFactory)
    {
        $this->contentFactory = $contentFactory;
    }

    /**
     * @return FactoryInterface
     */
    public function getConfigurationFactory()
    {
        return $this->configurationFactory;
    }

    /**
     * @param FactoryInterface $configurationFactory
     */
    public function setConfigurationFactory($configurationFactory)
    {
        $this->configurationFactory = $configurationFactory;
    }
}