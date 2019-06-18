<?php
/**
 * ContainerRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Twig;

use Enhavo\Bundle\BlockBundle\Entity\Container;
use Enhavo\Bundle\BlockBundle\Renderer\ContainerRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ContainerExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var ContainerRenderer
     */
    private $renderer;

    public function __construct(ContainerRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('block_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Container $container = null, $set = null, $onlyRenderTypes = null)
    {
        if($container) {
            return $this->renderer->render($container, $set, $onlyRenderTypes);
        }
        return '';
    }

    public function getName()
    {
        return 'block_render';
    }
}
