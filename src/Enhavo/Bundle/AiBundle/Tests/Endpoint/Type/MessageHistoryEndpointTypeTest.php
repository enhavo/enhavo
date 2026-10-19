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

use Enhavo\Bundle\AiBundle\Endpoint\Type\MessageHistoryEndpointType;
use Enhavo\Bundle\AiBundle\Entity\Message;
use Enhavo\Bundle\AiBundle\Entity\Session;
use Enhavo\Bundle\AiBundle\Model\MessageInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageHistoryEndpointTypeTest extends TestCase
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

    private function createInstance(): MessageHistoryEndpointType
    {
        $instance = new MessageHistoryEndpointType($this->resourceManager);
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function createMessage(string $role, string $content): Message
    {
        $message = new Message();
        $message->role = $role;
        $message->content = $content;
        $message->createdAt = new \DateTime('2026-10-17 12:00:00');

        return $message;
    }

    public function testHistory()
    {
        $session = new Session();
        $session->title = 'Chat';
        $session->addMessage($this->createMessage(MessageInterface::ROLE_USER, 'Hello'));
        $session->addMessage($this->createMessage(MessageInterface::ROLE_ASSISTANT, 'Hi there'));

        $this->repository->method('find')->with(1)->willReturn($session);

        $request = new Request([], [], ['id' => 1]);
        $data = new Data();
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, new Context($request));

        $this->assertEquals('Chat', $data['title']);
        $this->assertCount(2, $data['messages']);
        $this->assertEquals(MessageInterface::ROLE_USER, $data['messages'][0]['role']);
        $this->assertEquals('Hello', $data['messages'][0]['content']);
        $this->assertEquals(MessageInterface::ROLE_ASSISTANT, $data['messages'][1]['role']);
        $this->assertEquals((new \DateTime('2026-10-17 12:00:00'))->format(\DateTimeInterface::ATOM), $data['messages'][1]['createdAt']);
    }

    public function testSessionNotFound()
    {
        $this->repository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $request = new Request([], [], ['id' => 1]);
        $this->createInstance()->handleRequest(self::OPTIONS, $request, new Data(), new Context($request));
    }
}
