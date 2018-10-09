<?php
/**
 * GridRenderer.php
 *
 * @since 04/08/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Renderer;

use Enhavo\Bundle\GridBundle\Context\ContextBuilder;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Model\Context;
use Enhavo\Bundle\GridBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Enhavo\Bundle\GridBundle\Resolver\ItemResolver;
use Enhavo\Bundle\NavigationBundle\Exception\RenderException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridRenderer
{
    use ContainerAwareTrait;

    /**
     * @var ItemResolver
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
     * GridRenderer constructor.
     *
     * @param ItemResolver $resolver
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(ItemResolver $resolver, ContextBuilder $contextBuilder, $renderSets)
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
     * @param GridInterface|ItemInterface $content
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

        if($content instanceof GridInterface) {
            return $this->renderItems($content->getItems(), $templateSet, $onlyRenderTypes);
        }

        if($content instanceof ItemInterface) {
            return $this->renderItems([$content], $templateSet, $onlyRenderTypes);
        }

        throw new RenderException(sprintf('Content for render function must be type of "%s" or "%s"', GridInterface::class, ItemInterface::class));
    }

    /**
     * @param ItemInterface[] $items
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @return string
     */
    private function renderItems($items, $templateSet = null, $onlyRenderTypes = null)
    {
        $return = [];
        if($items) {
            $toRenderItems = [];
            /** @var $item Item */
            foreach($items as $item) {
                if (is_array($onlyRenderTypes) && !in_array($item->getName(), $onlyRenderTypes)) {
                    continue;
                }
                $toRenderItems[] = $item;
            }
            foreach($toRenderItems as $item) {
                $template = null;
                if(array_key_exists($templateSet, $this->renderSets) && isset($this->renderSets[$templateSet][$item->getName()])) {
                    $template = $this->renderSets[$templateSet][$item->getName()];
                }
                $return[] = $this->renderItem($item, $template);
            }
        }
        return join('', $return);
    }

    /**
     * @param ItemInterface $item
     * @param string $template
     * @return string
     */
    private function renderItem(ItemInterface $item, $template = null)
    {
        if($template === null) {
            $template = $this->resolver->resolveItem($item->getName())->getTemplate();
        }

        $context = null;
        if($item instanceof ContextAwareInterface) {
            $context = $item->getContext();
        }

        return $this->renderTemplate($template, [
            'data' => $item->getItemType(),
            'context' => $context
        ]);
    }
}
