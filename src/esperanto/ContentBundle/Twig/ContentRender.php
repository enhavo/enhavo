<?php
/**
 * ContentRender.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Twig;

use esperanto\ContentBundle\Entity\Content;
use esperanto\ContentBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;

class ContentRender extends \Twig_Extension
{
    protected $router;
    protected $engine;
    protected $container;
    protected $resolver;

    public function __construct(Router $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
        $this->resolver = $container->get('esperanto_content.item_type_resolver');
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

    public function render(Content $content, $set = null)
    {
        if(empty($content)) {
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
