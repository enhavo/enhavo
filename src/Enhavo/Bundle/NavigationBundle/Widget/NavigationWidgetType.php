<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationWidgetType extends AbstractWidgetType
{
    public function getType()
    {
        return 'navigation';
    }

    public function createViewData(array $options, $resource = null)
    {
        $navigation = $this->container->get('enhavo_navigation.navigation.repository')->findOneBy([
            'code' => $options['navigation'],
        ]);

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $route = $request->get('_route');

        return [
            'navigation' => $navigation,
            'route' => $route,
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'template' => 'theme/widget/navigation.html.twig',
        ])->setRequired([
            'navigation',
        ]);
    }
}
