<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiEndpointType implements EndpointTypeInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {

    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        $response = new JsonResponse($data->normalize(), $context->getStatusCode());

        foreach ($context->getHeaders() as $header) {
            $response->headers->set($header->getName(), $header->getValue());
        }

        return $response;
    }

    public function describe($options, Path $path)
    {

    }

    public static function getName(): ?string
    {
        return 'api';
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public function setParent(TypeInterface $parent)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
