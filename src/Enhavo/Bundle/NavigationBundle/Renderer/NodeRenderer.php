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
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;
use Twig\Environment;

class NodeRenderer
{
    use TemplateTrait;

    /** @var NavItemManager */
    private $itemManager;

    /** @var array */
    private $renderSets = [];

    /** @var Environment */
    private $twig;

    public function __construct(NavItemManager $itemManager, array $renderSets = null)
    {
        $this->itemManager = $itemManager;
        if(is_array($renderSets)) {
            $this->renderSets = $renderSets;
        }
    }

    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
    }

    private function renderView($template, $parameters = [])
    {
        return $this->twig->render($template, $parameters);
    }

    public function render(NodeInterface $node, $renderSet = null)
    {
        $item = $this->itemManager->getNavItem($node->getName());
        $template = $item->getRenderTemplate();

        if($renderSet && isset($this->renderSets[$renderSet][$node->getName()])) {
            $template = $this->renderSets[$renderSet][$node->getName()];
        }

        if($template === null) {
            if($renderSet) {
                throw new RenderException(sprintf('No template found to render node "%s" with render set "%s"', $node->getName(), $renderSet));
            }
            throw new RenderException(sprintf('No default template found for node type "%s"', $node->getName()));
        }

        return $this->renderView($this->getTemplate($template), [
            'node' => $node
        ]);
    }
}
