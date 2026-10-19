<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AiBundle\Endpoint\Type;

use Enhavo\Bundle\AiBundle\Model\MessageInterface;
use Enhavo\Bundle\AiBundle\Model\SessionInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageAddEndpointType extends AbstractEndpointType
{
    use MessageDataTrait;

    public function __construct(
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var SessionInterface|null $session */
        $session = $this->resourceManager->getRepository($options['session'])->find($request->attributes->get('id'));
        if (null === $session) {
            throw $this->createNotFoundException();
        }

        $content = trim((string) $request->getPayload()->get('content'));
        if ('' === $content) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Message content is empty';

            return;
        }

        /** @var MessageInterface $message */
        $message = $this->resourceManager->getFactory($options['message'])->createNew();
        $message->role = MessageInterface::ROLE_USER;
        $message->content = $content;
        $message->createdAt = new \DateTime();
        $session->addMessage($message);

        $this->resourceManager->save($message);

        $data['success'] = true;
        $data['message'] = $this->getMessageData($message);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'session' => 'enhavo_ai.session',
            'message' => 'enhavo_ai.message',
        ]);
    }

    public static function getName(): ?string
    {
        return 'ai_message_add';
    }
}
