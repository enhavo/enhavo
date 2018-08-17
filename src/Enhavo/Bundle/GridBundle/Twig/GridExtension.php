<?php
/**
 * GridRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Twig;

use Enhavo\Bundle\GridBundle\Entity\Column;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Model\Context;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grid_render', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('grid_item_render', array($this, 'renderItem'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('grid_column_render', array($this, 'renderColumn'), array('is_safe' => array('html')))
        );
    }

    public function render(Grid $grid = null, $set = null, $onlyRenderTypes = null)
    {
        if($grid) {
            return $this->container->get('enhavo_grid.renderer.grid_renderer')->render($grid, $set, $onlyRenderTypes);
        }
        return '';
    }


    public function renderItem(ItemInterface $item = null, Context $context = null, $template = null)
    {
        if($item) {
            if($context === null) {
                $context = new Context();
            }
            return $this->container->get('enhavo_grid.renderer.grid_renderer')->renderItem($item, $context, $template);
        }
        return '';
    }

    public function renderColumn(Column $column = null, Context $context = null, $set = null, $onlyRenderTypes = null)
    {
        if($column) {
            if($context === null) {
                $context = new Context();
            }
            return $this->container->get('enhavo_grid.renderer.grid_renderer')->renderColumn($column, $context, $set, $onlyRenderTypes);
        }
        return '';
    }

    public function getName()
    {
        return 'grid_render';
    }
}
