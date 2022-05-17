<?php

namespace Enhavo\Bundle\PaymentBundle\EventListener;

use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Payment\Model\PaymentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'sylius.payment.pre_create' => ['create', 1],
        ];
    }

    public function create(ResourceControllerEvent $event)
    {
        if($event->getSubject() instanceof Payment) {
            $event->getSubject()->setState(PaymentInterface::STATE_NEW);
        }
    }
}
