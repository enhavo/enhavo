<?php
/**
 * PaginationWidgetType.php
 *
 * @since 04/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Widget\Type;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginationWidgetType extends AbstractWidgetType
{
    public function createViewData($options, $resource = null)
    {
        $route = $options['route'];
        $routeParameters = $options['routeParameters'];
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if($request instanceof Request) {
            $route = $request->get('_route');
            $routeParameters = [];
            foreach($request->query as $key => $value) {
                if($key != 'page') {
                    $routeParameters[$key] = $value;
                }
            }
        }

        return [
            'resources' => $options['resources'],
            'route' => $route,
            'routeParameters' => $routeParameters
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'routeParameters' => [],
            'template' => 'theme/widget/pagination.html.twig',
        ])->setRequired([
            'resources',
            'route',
        ]);
    }

    public function getType()
    {
        return 'pagination';
    }
}
