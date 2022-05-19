<?php

namespace Enhavo\Bundle\PaymentBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Sylius\Component\Payment\Model\PaymentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => ['create', 1],
        ];
    }

    public function create(ResourceEvent $event)
    {
        if($event->getSubject() instanceof Payment) {
            $event->getSubject()->setState(PaymentInterface::STATE_NEW);
        }
    }
}
