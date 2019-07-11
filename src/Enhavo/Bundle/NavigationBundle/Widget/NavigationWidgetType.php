<?php
/**
 * NavigationWidgetType.php
 *
 * @since 25/06/18
 * @author gseidel
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

    public function createViewData($options, $resource = null)
    {
        $navigation = $this->container->get('enhavo_navigation.repository.navigation')->findOneBy([
            'code' => $options['navigation']
        ]);

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $route = $request->get('_route');

        return [
            'navigation' => $navigation,
            'route' => $route
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
