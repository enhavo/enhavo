<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 28.08.18
 * Time: 04:24
 */

namespace Enhavo\Bundle\NavigationBundle\Renderer;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\NavigationBundle\Exception\RenderException;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Environment;

class NodeRenderer
{
    use ContainerAwareTrait;
    use TemplateResolverTrait;

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

    /**
     * @param NodeInterface $node
     * @param null|string|array $renderSet
     * @return string
     * @throws RenderException
     */
    public function render(NodeInterface $node, $renderSet = null)
    {
        $item = $this->itemManager->getNavItem($node->getName());
        $template = $item->getTemplate();

        if (is_array($renderSet) && isset($renderSet[$node->getName()])) {
            $template = $renderSet[$node->getName()];
        } elseif(is_string($renderSet) && isset($this->renderSets[$renderSet][$node->getName()])) {
            $template = $this->renderSets[$renderSet][$node->getName()];
        }

        if($template === null) {
            if(is_string($renderSet)) {
                throw new RenderException(sprintf('No template found to render node "%s" with render set "%s"', $node->getName(), $renderSet));
            }
            throw new RenderException(sprintf('No default template found for node type "%s"', $node->getName()));
        }

        return $this->renderView($this->resolveTemplate($template), [
            'node' => $node
        ]);
    }
}
