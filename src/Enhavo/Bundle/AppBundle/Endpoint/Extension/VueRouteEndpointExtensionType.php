<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\TemplateEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueRouteEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly VueRouteProviderInterface $provider,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (!$options['vue_routes']) {
            return;
        }

        $groups = null;
        if (is_array($options['vue_routes']) || is_string($options['vue_routes'])) {
            $groups = $options['vue_routes'];
        }

        $routes = $this->provider->getRoutes($groups);
        $context->set('vue_routes', $routes);

        $data['vue_routes'] = $this->normalize($routes);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'vue_routes' => false,
        ]);
    }
}
