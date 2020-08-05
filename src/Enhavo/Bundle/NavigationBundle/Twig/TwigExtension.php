<?php
namespace Enhavo\Bundle\NavigationBundle\Twig;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\Renderer\NodeRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /** @var NavigationManager */
    private $navigationManager;

    /** @var NodeRenderer */
    private $nodeRenderer;

    /**
     * TwigExtension constructor.
     * @param NavigationManager $navigationManager
     * @param NodeRenderer $nodeRenderer
     */
    public function __construct(NavigationManager $navigationManager, NodeRenderer $nodeRenderer)
    {
        $this->navigationManager = $navigationManager;
        $this->nodeRenderer = $nodeRenderer;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('navigation_node_is_active', array($this, 'nodeIsActive')),
            new TwigFunction('navigation_node_render', array($this, 'renderNode'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param Node $node
     * @param array $options
     * @return boolean
     */
    public function nodeIsActive(Node $node, $options = [])
    {
        return $this->navigationManager->isActive($node, $options);
    }

    public function renderNode(NodeInterface $node, $renderSet = null)
    {
        return $this->nodeRenderer->render($node, $renderSet);
    }
}
