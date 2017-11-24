<?php
/**
 * PaginationWidget.php
 *
 * @since 04/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

class PaginationWidget extends AbstractType implements WidgetInterface
{
    public function render($options)
    {
        $template = $this->getOption('template', $options, 'EnhavoContentBundle:Widget:pagination.html.twig');
        $resources = $this->getRequiredOption('resources', $options);
        $route = $this->getRequiredOption('route', $options);
        $routeParameters = $this->getOption('routeParameters', $options, []);

        return $this->renderTemplate($template, [
            'resources' => $resources,
            'route' => $route,
            'routeParameters' => $routeParameters
        ]);
    }

    public function getType()
    {
        return 'pagination';
    }
}