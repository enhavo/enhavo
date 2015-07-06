<?php
/**
 * ContentRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ContentGridBundle\Twig;

use Enhavo\Bundle\ContentGridBundle\Entity\Content;
use Enhavo\Bundle\ContentGridBundle\Entity\Item;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class ContentRender extends \Twig_Extension
{
    protected $router;
    protected $engine;
    protected $container;
    protected $resolver;

    public function __construct(RouterInterface $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
        $this->resolver = $container->get('enhavo_content_grid.item_type_resolver');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('content_render', array($this, 'render'), array('is_safe' => array('html')))
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

    public function render(Content $content = null, $set = null)
    {
        if($content === null) {
            return '';
        }

        $html = array();
        $items = $content->getItems();
        if($items) {
            /** @var $item Item */
            foreach($items as $item) {
                $type = $item->getType();
                $data = $item->getItemType();
                $template = $this->resolver->getTemplate($type, $set);
                $html[] = $this->getEngine()->render($template, array('data' => $data));
            }
        }
        return join('', $html);
    }

    public function getName()
    {
        return 'content_render';
    }
}
