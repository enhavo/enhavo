<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Widget\Type;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class PaginationWidgetType extends AbstractWidgetType
{
    public function createViewData(array $options, $resource = null)
    {
        $pageParameter = $options['page_parameter'];
        if (null === $options['route']) {
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $route = $request->get('_route');
            $routeParameters = $options['routeParameters'];
            foreach ($request->query as $key => $value) {
                if ($key != $pageParameter) {
                    $routeParameters[$key] = $value;
                }
            }
            foreach ($request->attributes as $key => $value) {
                if ($key != $pageParameter && '_' != substr($key, 0, 1)) {
                    $routeParameters[$key] = $value;
                }
            }
        } else {
            $routeParameters = $options['routeParameters'];
            $route = $options['route'];
        }

        return [
            'resources' => $options['resources'],
            'route' => $route,
            'routeParameters' => $routeParameters,
            'pageParameter' => $pageParameter,
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'routeParameters' => [],
            'route' => null,
            'page_parameter' => 'page',
            'template' => 'theme/widget/pagination/pagination.html.twig',
        ]);

        $optionsResolver->setRequired([
            'resources',
        ]);
    }

    public function getType()
    {
        return 'pagination';
    }
}
