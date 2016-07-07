<?php
namespace Enhavo\Bundle\ThemeBundle\Box;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BoxRendererExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('theme_box_render', array($this, 'render'), array('is_safe' => array('html')))
        );
    }

    public function render($box)
    {
        $templateEngine = $this->container->get('templating');
        $boxCollector = $this->container->get('enhavo_theme.box_collector');

        $widgets = $boxCollector->getWidgets($box);
        $template = $boxCollector->getTemplate($box);

        return $templateEngine->render($template, [
            'widgets' => $widgets
        ]);
    }

    public function getName()
    {
        return 'theme_box_render';
    }
}