<?php

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository;
use Symfony\Component\HttpFoundation\Request;

class NewsletterStatsEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly NewsletterRepository $repository,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var NewsletterInterface $newsletter */
        $newsletter = $this->repository->find($request->get('id'));

        if ($newsletter === null) {
            throw $this->createNotFoundException();
        }

        $data->set('receivers', $this->normalize($newsletter->getReceivers()));
    }
}
