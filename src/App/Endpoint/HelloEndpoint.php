<?php

namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Enhavo\Bundle\AppBundle\Endpoint\ViewEndpointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/endpoint/world.{_format}', defaults: ['_format' => 'html'])]
#[Route(path: '/endpoint/hello.{_format}', name: 'app_theme_hello', defaults: ['_format' => 'html'])]
class HelloEndpoint extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $data->set('message', 'hello world!');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/endpoint/hello.html.twig'
        ]);
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }
}
