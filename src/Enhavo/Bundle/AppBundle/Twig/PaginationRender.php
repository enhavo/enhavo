<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Templating\EngineInterface;

class PaginationRender extends \Twig_Extension
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var string
     */
    private $template;

    /**
     * PaginationRender constructor.
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /**
     * @param EngineInterface $engine
     */
    public function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('pagination_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Pagerfanta $pagination, array $parameters = [])
    {
        return $this->engine->render($this->template, array_merge([
            'pagination' => $pagination
        ], $parameters));
    }

    public function getName()
    {
        return 'pagination_render';
    }
}
