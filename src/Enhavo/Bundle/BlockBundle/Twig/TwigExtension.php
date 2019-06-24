<?php
/**
 * ContainerRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Twig;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\BlockBundle\Renderer\BlockRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /**
     * @var BlockRenderer
     */
    private $renderer;

    public function __construct(BlockRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('block_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(NodeInterface $node, $resource = null, $templateSet = null, $onlyRenderTypes = null)
    {
        return $this->renderer->render($node, $resource, $templateSet, $onlyRenderTypes);
    }
}
