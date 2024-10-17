<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TemplateAdminEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $vueRoutes = [];
        if ($data->has('vue_routes')) {
            $vueRoutes = $data->get('vue_routes');
        }

        $vueRoutes[] = [
            'name' => $request->attributes->get('route'),
            'path' => $request->getPathInfo(),
            'meta' => $options['meta'],
            'component' => $options['component'],
        ];

        $data->set('vue_routes', $vueRoutes);
        $data->set('component', 'app-app');
        $data->set('props', []);
    }

    public static function getParentType(): ?string
    {
        return TemplateEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => null,
            'meta' => [],
            'routes' => ['template_admin'],
            'translations' => ['javascript'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'template_admin';
    }
}
