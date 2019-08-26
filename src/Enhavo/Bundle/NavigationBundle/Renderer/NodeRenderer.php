<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 28.08.18
 * Time: 04:24
 */

namespace Enhavo\Bundle\NavigationBundle\Renderer;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\NavigationBundle\Exception\RenderException;
use Enhavo\Bundle\NavigationBundle\Item\ItemManager;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class NodeRenderer
{
    use ContainerAwareTrait;
    use TemplateTrait;

    /**
     * @var ItemManager
     */
    private $itemManager;

    /**
     * @var array
     */
    private $renderSets = [];

    public function __construct(ItemManager $itemManager, array $renderSets = null)
    {
        $this->itemManager = $itemManager;
        if(is_array($renderSets)) {
            $this->renderSets = $renderSets;
        }
    }

    private function renderView($template, $parameters = [])
    {
        return $this->container->get('twig')->render($template, $parameters);
    }

    public function render(NodeInterface $node, $renderSet = null)
    {
        $item = $this->itemManager->getItem($node->getType());
        $template = $item->getRenderTemplate();

        if($renderSet && isset($this->renderSets[$renderSet][$node->getType()])) {
            $template = $this->renderSets[$renderSet][$node->getType()];
        }

        if($template === null) {
            if($renderSet) {
                throw new RenderException(sprintf('No template found to render node "%s" with render set "%s"', $node->getType(), $renderSet));
            }
            throw new RenderException(sprintf('No default template found for node type "%s"', $node->getType()));
        }

        return $this->renderView($this->getTemplate($template), [
            'node' => $node
        ]);
    }
}
