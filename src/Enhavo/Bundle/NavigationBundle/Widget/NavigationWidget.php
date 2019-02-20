<?php
/**
 * NavigationWidget.php
 *
 * @since 25/06/18
 * @author gseidel
 */

namespace Enhavo\Bundle\NavigationBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;

class NavigationWidget extends AbstractType implements WidgetInterface
{
    public function getType()
    {
        return 'navigation';
    }

    public function render($options)
    {
        $navigationCode = $this->getRequiredOption('navigation', $options);
        $template = $this->getOption('template', $options, 'EnhavoNavigationBundle:Theme:Widget/navigation.html.twig');

        $navigation = $this->container->get('enhavo_navigation.repository.navigation')->findOneBy([
            'code' => $navigationCode
        ]);

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $route = $request->get('_route');

        return $this->renderTemplate($template, [
            'navigation' => $navigation,
            'route' => $route
        ]);
    }
}
