<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

        if ($this->contentFactory) {
            $content = $this->contentFactory->createNew();
            $node->setContent($content);
        }

        if ($this->configurationFactory) {
            $configuration = $this->configurationFactory->createNew();
            $node->setConfiguration($configuration);
        }

        $node->setType($this->name);

        return $node;
    }
}
