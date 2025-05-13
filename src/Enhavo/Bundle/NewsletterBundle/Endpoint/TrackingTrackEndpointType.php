<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Repository\ReceiverRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackingTrackEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ReceiverRepository $receiverRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $token = $request->get('token');

        /** @var Receiver $receiver */
        $receiver = $this->receiverRepository->findOneBy([
            'token' => $token,
        ]);

        $response = new BinaryFileResponse(sprintf('%s/../Resources/image/pixel.png', __DIR__));

        if (null !== $receiver) {
            $receiver->trackOpen();
            $this->entityManager->flush();
        }

        $context->setResponse($response);
    }
}
