<?php

namespace Enhavo\Bundle\PaymentBundle\EventListener;

use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
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
