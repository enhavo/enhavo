<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.04.18
 * Time: 13:47
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

class NodeFactory
{
    /**
     * @var string
     */
    private $class;

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
}
