<?php

namespace Enhavo\Bundle\ApiBundle\EventListener;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class EndpointRouteListener
{
    public function __construct(
        private FactoryInterface $factory,
    )
    {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ($event->getRequest()->attributes->has('_endpoint')) {

            $endpointConfiguration = $event->getRequest()->attributes->get('_endpoint');

            /** @var Endpoint $endpoint */
            $endpoint = $this->factory->create($endpointConfiguration);
            $response = $endpoint->getResponse($event->getRequest());
            $event->setResponse($response);
        }
    }
}
