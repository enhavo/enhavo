<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AiBundle\Tests\Endpoint\Type;

use Enhavo\Bundle\AiBundle\Endpoint\Type\SessionListEndpointType;
use Enhavo\Bundle\AiBundle\Entity\Session;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SessionListEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private const OPTIONS = ['session' => 'enhavo_ai.session'];

    private ResourceManager|MockObject $resourceManager;
    private EntityRepository|MockObject $repository;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        $this->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $this->resourceManager->method('getRepository')->willReturn($this->repository);
    }

    private function createInstance(): SessionListEndpointType
    {
        $instance = new SessionListEndpointType($this->resourceManager);
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function createSession(string $title, ?\DateTime $updatedAt): Session
    {
        $session = new Session();
        $session->title = $title;
        $session->updatedAt = $updatedAt;

        return $session;
    }

    public function testList()
    {
        $updatedAt = new \DateTime('2026-10-17 12:00:00');
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with([], ['updatedAt' => 'DESC', 'id' => 'DESC'])
            ->willReturn([
                $this->createSession('Second', $updatedAt),
                $this->createSession('First', null),
            ]);

        $request = new Request();
        $data = new Data();
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, new Context($request));

        $this->assertCount(2, $data['sessions']);
        $this->assertEquals('Second', $data['sessions'][0]['title']);
        $this->assertEquals($updatedAt->format(\DateTimeInterface::ATOM), $data['sessions'][0]['updatedAt']);
        $this->assertEquals('First', $data['sessions'][1]['title']);
        $this->assertNull($data['sessions'][1]['updatedAt']);
    }

    public function testEmptyList()
    {
        $this->repository->method('findBy')->willReturn([]);

        $request = new Request();
        $data = new Data();
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, new Context($request));

        $this->assertSame([], $data['sessions']);
    }
}
