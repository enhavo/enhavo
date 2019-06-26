<?php
/**
 * ContainerRenderer.php
 *
 * @since 04/08/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Renderer;

use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Exception\RenderException;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BlockRenderer
{
    use ContainerAwareTrait;

    /**
     * @var BlockManager
     */
    private $blockManager;

    /**
     * @var string[]
     */
    private $renderSets;

    /**
     * ContainerRenderer constructor.
     *
     * @param BlockManager $blockManager
     * @param array $renderSets
     */
    public function __construct(BlockManager $blockManager, $renderSets)
    {
        $this->blockManager = $blockManager;
        $this->renderSets = $renderSets;
    }

    /**
     * @param string $template
     * @param array $parameters
     *
     * @return string
     */
    private function renderTemplate($template, $parameters = [])
    {
        return $this->container->get('templating')->render($template, $parameters);
    }

    /**
     * @param NodeInterface $node
     * @param object $resource
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @throws RenderException
     * @return string
     */
    public function render(NodeInterface $node, $resource = null, $templateSet = null, $onlyRenderTypes = null)
    {
        if($node->getViewData() === null) {
            $this->blockManager->createViewData($node, $resource);
        }

        if($node->getType() === NodeInterface::TYPE_BLOCK) {
            return $this->renderNodes([$node], $templateSet, $onlyRenderTypes);
        }

        if($node->getType() === NodeInterface::TYPE_ROOT || $node->getType() === NodeInterface::TYPE_LIST) {
            return $this->renderNodes($node->getChildren(), $templateSet, $onlyRenderTypes);
        }

        throw new RenderException(sprintf('Node type "%s" is not valid', $node->getType()));
    }

    /**
     * @param NodeInterface[] $nodes
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @return string
     */
    private function renderNodes($nodes, $templateSet = null, $onlyRenderTypes = null)
    {
        $return = [];
        if($nodes) {
            $toRenderNodes = [];
            foreach($nodes as $node) {
                if (is_array($onlyRenderTypes) && !in_array($node->getName(), $onlyRenderTypes)) {
                    continue;
                }
                $toRenderNodes[] = $node;
            }
            foreach($toRenderNodes as $node) {
                $template = null;
                if(array_key_exists($templateSet, $this->renderSets) && isset($this->renderSets[$templateSet][$node->getName()])) {
                    $template = $this->renderSets[$templateSet][$node->getName()];
                }
                $return[] = $this->renderNode($node, $template);
            }
        }
        return join('', $return);
    }

    /**
     * @param NodeInterface $node
     * @param string $template
     * @return string
     * @throws \Exception
     */
    private function renderNode(NodeInterface $node, $template = null)
    {
        if($template === null) {
            $block = $this->blockManager->getBlock($node->getName());
            if($node->getTemplate()) {
                if(!isset($block->getTemplate()[$node->getTemplate()])) {
                    throw new \Exception(sprintf('Can\' render block "%s", because the template "%s" was set, but is not available in the configuration', $block->getName(), $node->getTemplate()));
                }
                $template = $block->getTemplate()[$node->getTemplate()]['template'];
            } else {
                $template = $block->getTemplate();
                if(!is_string($template)) {
                    throw new \Exception(sprintf('Can\' render block "%s", because the template is empty. Please set the template in the admin', $block->getName()));
                }
            }
        }

        $viewData = $node->getViewData();

        return $this->renderTemplate($template, $viewData);
    }
}
