<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UrlWidget extends AbstractTableWidget
{
    public function render($options, $resource)
    {
        $resolverType = $this->getOption('resolver_type', $options, 'default');
        /** @var Router $router */
        $router = $this->container->get('enhavo_routing.router');
        $url = $router->generate($resource, [], UrlGenerator::ABSOLUTE_PATH, $resolverType);

        $template = $this->getOption('template', $options, 'EnhavoAppBundle:TableWidget:url.html.twig');
        $target = $this->getOption('target', $options, '_blank');
        $icon = $this->getOption('icon', $options, 'link');

        return $this->renderTemplate($template, array(
            'url' => $url,
            'target' => $target,
            'icon' => $icon,
        ));
    }

    public function getType()
    {
        return 'url';
    }
}