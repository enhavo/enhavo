<?php
/**
 * GridRenderer.php
 *
 * @since 04/08/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Renderer;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Resolver\ItemResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Templating\EngineInterface;

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
     * @return EngineInterface
     */
    private function renderTemplate($template, $parameters = [])
    {
        return $this->container->get('templating')->render($template, $parameters);
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
                        $html[] = $this->renderTemplate($template, array('data' => $data));
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
}
