<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $data->set('component', $options['component']);
        $data->set('props', $options['props']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => '/admin/base.html.twig',
            'component' => 'app-app',
            'props' => [],
        ]);
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'admin';
    }
}
