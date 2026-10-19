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
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageDeleteEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var MessageInterface|null $message */
        $message = $this->resourceManager->getRepository($options['message'])->find($request->attributes->get('id'));
        if (null === $message) {
            throw $this->createNotFoundException();
        }

        $message->session?->removeMessage($message);
        $this->resourceManager->delete($message);

        $data['success'] = true;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'message' => 'enhavo_ai.message',
        ]);
    }

    public static function getName(): ?string
    {
        return 'ai_message_delete';
    }
}
