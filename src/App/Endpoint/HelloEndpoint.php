<?php

namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Method;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Enhavo\Bundle\AppBundle\Endpoint\ViewEndpointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/hello', name: 'app_theme_hello', defaults: ['_format' => 'html'])]
#[Route(path: '/api/hello', defaults: ['_format' => 'json', '_describe' => true])]
class HelloEndpoint extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $data->set('message', 'hello world!');
    }

    public function describe($options, Path $path)
    {
        $path
            ->method(Method::GET)
                ->description('Hello World Api Endpoint')
                ->summary('Hello World Summary')
                ->parameter('place')
                    ->in('query')
                    ->description('The place to be')
                    ->required(false)
                ->end()
            ->end()
        ;
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
