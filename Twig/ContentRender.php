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
use Symfony\Component\Templating\EngineInterface;

class ContentRender extends \Twig_Extension
{
    protected $router;
    protected $engine;
    protected $container;

    public function __construct($router, $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('content_render', array($this, 'render'), array('is_safe' => array('html'))),
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

    public function render(Content $content)
    {
        if(empty($content)) {
            return '';
        }

        $html = array();
        $items = $content->getItems();
        if($items) {
            /** @var $item Item */
            foreach($items as $item) {
                $type = $item->getConfiguration()->getType();
                $data = $item->getConfiguration()->getData();
                $template = sprintf('esperantoContentBundle:Item:%s.html.twig', $type);
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