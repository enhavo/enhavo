<?php
namespace Enhavo\Bundle\NavigationBundle\Twig;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\NodeManager;
use Enhavo\Bundle\NavigationBundle\Renderer\NodeRenderer;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var NodeManager
     */
    private $nodeManager;

    /**
     * @var NodeRenderer
     */
    private $nodeRenderer;

    /**
     * TwigExtension constructor.
     * @param NodeManager $nodeManager
     * @param NodeRenderer $nodeRenderer
     */
    public function __construct(NodeManager $nodeManager, NodeRenderer $nodeRenderer)
    {
        $this->nodeManager = $nodeManager;
        $this->nodeRenderer = $nodeRenderer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('navigation_node_is_active', array($this, 'nodeIsActive')),
            new \Twig_SimpleFunction('navigation_node_render', array($this, 'renderNode'), array('is_safe' => array('html'))),
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

    public function renderNode(NodeInterface $node, $renderSet = null)
    {
        return $this->nodeRenderer->render($node, $renderSet);
    }
}
