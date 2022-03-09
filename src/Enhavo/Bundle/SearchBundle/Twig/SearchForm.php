<?php

namespace Enhavo\Bundle\SearchBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SearchForm extends AbstractExtension
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('search_form', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($type = 'search', $entities = null, $fields = null)
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('twig');
        }

        $template =  $this->container->getParameter('enhavo_search.'.$type.'.template');

        return $this->templateEngine->render($template, array(
            'type' => $type,
            'entities' => $entities,
            'fields' => $fields
        ));
    }

    public function getName()
    {
        return 'search_render';
    }
}
