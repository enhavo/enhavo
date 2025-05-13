<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Tests;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait EndpointTestTrait
{
    public function enhanceEndpoint(EndpointTypeInterface $type)
    {
        if ($type instanceof AbstractEndpointType) {
            $container = new Container();
            $container->set('serializer', new Serializer([new ObjectNormalizer()]));
            $type->setContainer($container);
        }
    }
}
