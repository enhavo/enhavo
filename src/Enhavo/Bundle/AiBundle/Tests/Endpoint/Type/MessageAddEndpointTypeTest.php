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

use Enhavo\Bundle\AiBundle\Endpoint\Type\MessageAddEndpointType;
use Enhavo\Bundle\AiBundle\Entity\Message;
use Enhavo\Bundle\AiBundle\Entity\Session;
use Enhavo\Bundle\AiBundle\Model\MessageInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageAddEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private const OPTIONS = ['session' => 'enhavo_ai.session', 'message' => 'enhavo_ai.message'];

    private ResourceManager|MockObject $resourceManager;
    private EntityRepository|MockObject $repository;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        $factory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $factory->method('createNew')->willReturnCallback(fn () => new Message());

        $this->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $this->resourceManager->method('getRepository')->willReturn($this->repository);
        $this->resourceManager->method('getFactory')->willReturn($factory);
    }

    private function createInstance(): MessageAddEndpointType
    {
        $instance = new MessageAddEndpointType($this->resourceManager);
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function createRequest(array $payload): Request
    {
        $request = new Request([], [], ['id' => 1], [], [], ['REQUEST_METHOD' => 'POST', 'CONTENT_TYPE' => 'application/json'], json_encode($payload));

        return $request;
    }

    public function testAddMessage()
    {
        $session = new Session();
        $this->repository->method('find')->with(1)->willReturn($session);
        $this->resourceManager->expects($this->once())->method('save')->with($this->isInstanceOf(Message::class));

        $request = $this->createRequest(['content' => ' Hello ']);
        $data = new Data();
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, new Context($request));

        $this->assertCount(1, $session->messages);
        $message = $session->messages->first();
        $this->assertSame($session, $message->session);
        $this->assertEquals(MessageInterface::ROLE_USER, $message->role);
        $this->assertEquals('Hello', $message->content);
        $this->assertNotNull($message->createdAt);
        $this->assertTrue($data['success']);
        $this->assertEquals('Hello', $data['message']['content']);
    }

    public function testEmptyContent()
    {
        $session = new Session();
        $this->repository->method('find')->willReturn($session);
        $this->resourceManager->expects($this->never())->method('save');

        $request = $this->createRequest(['content' => '  ']);
        $data = new Data();
        $context = new Context($request);
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, $context);

        $this->assertEquals(400, $context->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertCount(0, $session->messages);
    }

    public function testSessionNotFound()
    {
        $this->repository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $request = $this->createRequest(['content' => 'Hello']);
        $this->createInstance()->handleRequest(self::OPTIONS, $request, new Data(), new Context($request));
    }
}
