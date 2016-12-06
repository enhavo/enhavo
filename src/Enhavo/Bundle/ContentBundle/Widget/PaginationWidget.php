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
        return $this->renderTemplate('EnhavoContentBundle:Widget:pagination.html.twig', [
            'resources' => $options['resources'],
            'route' => $options['route'],
            'routeParameters' => $this->getOption('routeParameters', $options, [])
        ]);
    }

    public function getType()
    {
        return 'pagination';
    }
}