<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Enhavo\Bundle\ApiBundle\Profiler\EndpointDataCollector;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EndpointTest extends TestCase
{
    public function createInstance(EndpointDependencies $dependencies)
    {
        $endpoint = new Endpoint($dependencies->type, $dependencies->parents, $dependencies->options, $dependencies->key, $dependencies->extensions, $dependencies->dataCollector);

        return $endpoint;
    }

    public function createDependencies()
    {
        $dependencies = new EndpointDependencies();
        $dependencies->type = new MainType();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->dataCollector = $this->getMockBuilder(EndpointDataCollector::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function testGetResponse()
    {
        $dependencies = $this->createDependencies();
        $endpoint = $this->createInstance($dependencies);

        $dependencies->type->data = [
            'hello' => '123',
        ];

        $response = $endpoint->getResponse($dependencies->request);

        $this->assertEquals([
            'hello' => '123',
        ], json_decode($response->getContent(), true));
    }
}

class EndpointDependencies
{
    public MainType $type;
    public array $parents = [];
    public array $options = [];
    public string $key = 'key';
    public array $extensions = [];
    public Request $request;
    public EndpointDataCollector $dataCollector;
}

class MainType implements EndpointTypeInterface
{
    public $data = [];

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        foreach ($this->data as $key => $value) {
            $data->set($key, $value);
        }
    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        return new JsonResponse($data->normalize());
    }

    public function describe($options, Path $path)
    {
    }

    public static function getName(): ?string
    {
    }

    public static function getParentType(): ?string
    {
    }

    public function setParent(TypeInterface $parent)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
