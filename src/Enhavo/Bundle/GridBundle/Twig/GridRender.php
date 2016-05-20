<?php
/**
 * GridRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Twig;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class GridRender extends \Twig_Extension
{
    protected $router;
    protected $engine;
    protected $container;
    protected $resolver;

    public function __construct(RouterInterface $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
        $this->resolver = $container->get('enhavo_grid.item_type_resolver');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grid_render', array($this, 'render'), array('is_safe' => array('html')))
        );
    }

    /**
     * @return EngineInterface
     */
    public function getEngine()
    {
        if($this->engine == null) {
            $this->engine = $this->container->get('templating');
        }
        return $this->engine;
    }

    public function render(Grid $grid = null, $set = null, $onlyRenderTypes = null)
    {
        if($grid === null) {
            return '';
        }

        $html = array();
        $items = $grid->getItems();
        if($items) {
            /** @var $item Item */
            foreach($items as $item) {
                $type = $item->getType();
                if(!is_array($onlyRenderTypes)) {
                    if($onlyRenderTypes == $type||$onlyRenderTypes == null) {
                        $data = $item->getItemType();
                        $template = $this->resolver->getTemplate($type, $set);
                        $html[] = $this->getEngine()->render($template, array('data' => $data));
                    }
                } else {
                    foreach($onlyRenderTypes as $onlyRenderType) {
                        if ($onlyRenderType == $type) {
                            $data = $item->getItemType();
                            $template = $this->resolver->getTemplate($type, $set);
                            $html[] = $this->getEngine()->render($template, array('data' => $data));
                        }
                    }
                }
            }
        }
        return join('', $html);
    }

    public function getName()
    {
        return 'grid_render';
    }
}
