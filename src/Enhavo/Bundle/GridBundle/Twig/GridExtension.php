<?php
/**
 * GridRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Twig;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grid_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Grid $grid = null, $set = null, $onlyRenderTypes = null)
    {
        if($grid) {
            return $this->container->get('enhavo_grid.renderer.grid_renderer')->render($grid, $set, $onlyRenderTypes);
        }
        return '';
    }

    public function getName()
    {
        return 'grid_render';
    }
}
