<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 16:21
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

class Factory implements FactoryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * @var object|null
     */
    private $content;

    public function __construct($class, $name = null, $content = null)
    {
        $this->class = $class;
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @return NodeInterface
     */
    public function createNew()
    {
        $class = $this->class;
        /** @var NodeInterface $node */
        $node = new $class();

        if($this->name) {
            $node->setType($this->name);
        }

        if($this->content) {
            $node->setContent($this->content);
        }
        return $node;
    }
}