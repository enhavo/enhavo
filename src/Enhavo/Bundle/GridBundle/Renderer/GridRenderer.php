<?php
/**
 * GridRenderer.php
 *
 * @since 04/08/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Renderer;

use Enhavo\Bundle\GridBundle\Entity\Column;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Model\Context;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Enhavo\Bundle\GridBundle\Resolver\ItemResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridRenderer
{
    use ContainerAwareTrait;

    /**
     * @var ItemResolver
     */
    private $resolver;

    public function __construct(ItemResolver $resolver)
    {
        $this->resolver = $resolver;
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
     * @param Grid|null $grid
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @return string
     */
    public function render(Grid $grid = null, $templateSet = null, $onlyRenderTypes = null)
    {
        if($grid === null) {
            return '';
        }

        /** @var ItemInterface[] $items */
        $items = $grid->getItems();
        $context = new Context();
        $context->setData($grid);

        return $this->renderItems($items, $context, $templateSet, $onlyRenderTypes);
    }

    /**
     * @param Column $column
     * @param Context $context
     * @param string[]|null $templateSet
     * @param string[]|null $onlyRenderTypes
     * @return string
     */
    public function renderColumn(Column $column, Context $context, $templateSet = null, $onlyRenderTypes = null)
    {
        if($column === null) {
            return '';
        }

        /** @var ItemInterface[] $items */
        $items = $column->getItems();
        $currentContext = new Context();
        $currentContext->setData($column);
        $currentContext->setParent($context);

        return $this->renderItems($items, $currentContext, $templateSet, $onlyRenderTypes);
    }

    /**
     * @param ItemInterface[] $items
     * @param Context $context
     * @param array|null $templateSet
     * @param array|null $onlyRenderTypes
     * @return string
     */
    private function renderItems($items, Context $context, $templateSet = null, $onlyRenderTypes = null)
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

            /** @var Context[] $contextMap */
            $contextMap = [];
            for($i = 0; $i < count($toRenderItems); $i++) {
                $contextMap[$i] = new Context();
                $contextMap[$i]->setParent($context);
                $contextMap[$i]->setData($item);
                $item = $toRenderItems[$i];
                if($i > 0) {
                    $contextMap[$i-1]->setNext($contextMap[$i]);
                    $contextMap[$i]->setBefore($contextMap[$i-1]);
                }
            }

            for($i = 0; $i < count($toRenderItems); $i++) {
                $template = null;
                if(is_array($templateSet) && array_key_exists($item->getName(), $templateSet)) {
                    $template = $templateSet[$item->getName()];
                }
                $return[] = $this->renderItem($toRenderItems[$i], $contextMap[$i], $template);
            }
        }
        return join('', $return);
    }

    /**
     * @param ItemInterface $item
     * @param Context $context
     * @param string $template
     * @return string
     */
    public function renderItem(ItemInterface $item, Context $context, $template = null)
    {
        if($template === null) {
            $template = $this->resolver->resolveItem($item->getName())->getTemplate();
        }

        $context->setData($item);

        return $this->renderTemplate($template, [
            'data' => $item->getItemType(),
            'context' => $context
        ]);
    }
}
