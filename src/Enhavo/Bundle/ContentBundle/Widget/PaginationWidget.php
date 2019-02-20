<?php
/**
 * PaginationWidget.php
 *
 * @since 04/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationWidget extends AbstractType implements WidgetInterface
{
    public function render($options)
    {
        $template = $this->getOption('template', $options, 'EnhavoContentBundle:Widget:pagination.html.twig');
        $resources = $this->getRequiredOption('resources', $options);

        $route = $this->getOption('route', $options, null);
        $routeParameters = $this->getOption('routeParameters', $options, []);
        $request = $this->getOption('request', $options, null);

        if($request instanceof Request) {
            $route = $request->get('_route');
            $routeParameters = [];
            foreach($request->query as $key => $value) {
                if($key != 'page') {
                    $routeParameters[$key] = $value;
                }
            }
        }

        if($route === null) {
            throw new \InvalidArgumentException(sprintf('Define parameter "route" or "request"'));
        }

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
