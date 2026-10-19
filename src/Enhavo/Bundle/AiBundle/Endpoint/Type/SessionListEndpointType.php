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

use Enhavo\Bundle\AiBundle\Model\SessionInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionListEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var SessionInterface[] $sessions */
        $sessions = $this->resourceManager->getRepository($options['session'])->findBy([], ['updatedAt' => 'DESC', 'id' => 'DESC']);

        $data['sessions'] = array_map(fn (SessionInterface $session) => [
            'id' => $session->id,
            'title' => $session->title,
            'createdAt' => $session->createdAt?->format(\DateTimeInterface::ATOM),
            'updatedAt' => $session->updatedAt?->format(\DateTimeInterface::ATOM),
        ], $sessions);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'session' => 'enhavo_ai.session',
        ]);
    }

    public static function getName(): ?string
    {
        return 'ai_session_list';
    }
}
