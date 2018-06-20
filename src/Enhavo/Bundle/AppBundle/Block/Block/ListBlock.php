<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class ListBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        $translationDomain = $this->getOption('translationDomain', $parameters, null);
        $route = $this->getRequiredOption('list_route', $parameters);
        $template = $this->getOption('template', $parameters, 'EnhavoAppBundle:Block:list.html.twig');

        return $this->renderTemplate($template, [
            'app' => $this->getOption('app', $parameters, 'app/app/Adapter/Block/EnhavoAdapter'),
            'list_route' => $route,
            'list_route_parameters' => $this->getOption('route_parameters', $parameters, null),
            'update_route_parameters' => $this->getOption('update_route_parameters', $parameters, null),
            'update_route' => $this->getOption('update_route', $parameters, null),
            'translationDomain' => $translationDomain,
        ]);
    }

    public function getType()
    {
        return 'list';
    }
}