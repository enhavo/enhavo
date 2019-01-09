<?php
/**
 * GridRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Twig;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Renderer\GridRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var GridRenderer
     */
    private $renderer;

    public function __construct(GridRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grid_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Grid $grid = null, $set = null, $onlyRenderTypes = null)
    {
        if($grid) {
            return $this->renderer->render($grid, $set, $onlyRenderTypes);
        }
        return '';
    }

    public function getName()
    {
        return 'grid_render';
    }
}
