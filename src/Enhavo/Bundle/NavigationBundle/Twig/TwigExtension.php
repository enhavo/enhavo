<?php
namespace Enhavo\Bundle\NavigationBundle\Twig;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Node\NodeManager;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var NodeManager
     */
    protected $nodeManager;

    /**
     * TwigExtension constructor.
     * @param NodeManager $nodeManager
     */
    public function __construct(NodeManager $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('node_is_active', array($this, 'nodeIsActive')),
        );
    }

    /**
     * @param Node $node
     * @param array $options
     * @return boolean
     */
    public function nodeIsActive(Node $node, $options = [])
    {
        return $this->nodeManager->isActive($node, $options);
    }
}
