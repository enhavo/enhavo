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

use Enhavo\Bundle\AiBundle\Endpoint\Type\MessageDeleteEndpointType;
use Enhavo\Bundle\AiBundle\Entity\Message;
use Enhavo\Bundle\AiBundle\Entity\Session;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageDeleteEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private const OPTIONS = ['message' => 'enhavo_ai.message'];

    private ResourceManager|MockObject $resourceManager;
    private EntityRepository|MockObject $repository;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        $this->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $this->resourceManager->method('getRepository')->willReturn($this->repository);
    }

    private function createInstance(): MessageDeleteEndpointType
    {
        $instance = new MessageDeleteEndpointType($this->resourceManager);
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    public function testDeleteMessage()
    {
        $session = new Session();
        $message = new Message();
        $session->addMessage($message);

        $this->repository->method('find')->with(1)->willReturn($message);
        $this->resourceManager->expects($this->once())->method('delete')->with($message);

        $request = new Request([], [], ['id' => 1]);
        $data = new Data();
        $this->createInstance()->handleRequest(self::OPTIONS, $request, $data, new Context($request));

        $this->assertCount(0, $session->messages);
        $this->assertNull($message->session);
        $this->assertTrue($data['success']);
    }

    public function testMessageNotFound()
    {
        $this->repository->method('find')->willReturn(null);
        $this->resourceManager->expects($this->never())->method('delete');

        $this->expectException(NotFoundHttpException::class);

        $request = new Request([], [], ['id' => 1]);
        $this->createInstance()->handleRequest(self::OPTIONS, $request, new Data(), new Context($request));
    }
}
