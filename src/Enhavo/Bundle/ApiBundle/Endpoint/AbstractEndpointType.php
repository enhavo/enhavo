<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\Type\ApiEndpointType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractEndpointType extends AbstractType implements EndpointTypeInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        return $this->parent->handleRequest($options, $request, $data, $context);
    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        return $this->parent->getResponse($options, $request, $data, $context);
    }

    public function describe($options, Path $path)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public static function getName(): ?string
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return ApiEndpointType::class;
    }
}
