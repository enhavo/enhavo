<?php
/**
 * ContainerRenderer.php
 *
 * @since 04/08/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Renderer;

use Enhavo\Bundle\BlockBundle\Context\ContextBuilder;
use Enhavo\Bundle\BlockBundle\Entity\Block;
use Enhavo\Bundle\BlockBundle\Model\Context;
use Enhavo\Bundle\BlockBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Resolver\BlockResolver;
use Enhavo\Bundle\NavigationBundle\Exception\RenderException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ContainerRenderer
{
    use ContainerAwareTrait;

    /**
     * @var BlockResolver
     */
    private $resolver;

    /**
     * @var ContextBuilder
     */
    private $contextBuilder;

    /**
     * @var string[]
     */
    private $renderSets;

    /**
     * ContainerRenderer constructor.
     *
     * @param BlockResolver $resolver
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(BlockResolver $resolver, ContextBuilder $contextBuilder, $renderSets)
    {
        $this->resolver = $resolver;
        $this->contextBuilder = $contextBuilder;
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
     * @param ContainerInterface|BlockInterface $content
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @param Context|null $parentContext
     * @throws RenderException
     * @return string
     */
    public function render($content = null, $templateSet = null, $onlyRenderTypes = null, Context $parentContext = null)
    {
        if($content instanceof ContextAwareInterface && empty($content->getContext())) {
            $this->contextBuilder->build($content, $parentContext);
        }

        if($content instanceof ContainerInterface) {
            return $this->renderBlocks($content->getBlocks(), $templateSet, $onlyRenderTypes);
        }

        if($content instanceof BlockInterface) {
            return $this->renderBlocks([$content], $templateSet, $onlyRenderTypes);
        }

        throw new RenderException(sprintf('Content for render function must be type of "%s" or "%s"', ContainerInterface::class, BlockInterface::class));
    }

    /**
     * @param BlockInterface[] $blocks
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @return string
     */
    private function renderBlocks($blocks, $templateSet = null, $onlyRenderTypes = null)
    {
        $return = [];
        if($blocks) {
            $toRenderBlocks = [];
            /** @var $block Block */
            foreach($blocks as $block) {
                if (is_array($onlyRenderTypes) && !in_array($block->getName(), $onlyRenderTypes)) {
                    continue;
                }
                $toRenderBlocks[] = $block;
            }
            foreach($toRenderBlocks as $block) {
                $template = null;
                if(array_key_exists($templateSet, $this->renderSets) && isset($this->renderSets[$templateSet][$block->getName()])) {
                    $template = $this->renderSets[$templateSet][$block->getName()];
                }
                $return[] = $this->renderBlock($block, $template);
            }
        }
        return join('', $return);
    }

    /**
     * @param BlockInterface $block
     * @param string $template
     * @return string
     */
    private function renderBlock(BlockInterface $block, $template = null)
    {
        if($template === null) {
            $template = $this->resolver->resolveItem($block->getName())->getTemplate();
        }

        $context = null;
        if($block instanceof ContextAwareInterface) {
            $context = $block->getContext();
        }

        return $this->renderTemplate($template, [
            'data' => $block->getBlockType(),
            'context' => $context
        ]);
    }
}
