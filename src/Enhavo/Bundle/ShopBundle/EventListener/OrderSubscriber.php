<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class OrderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            'sylius.order.post_complete' => 'complete',
        ];
    }

    public function complete(ResourceEvent $event)
    {
        $order = $event->getSubject();
        if($order instanceof OrderInterface) {
            $event->stop('Redirect');
            $event->setResponse(new RedirectResponse($this->router->generate('enhavo_shop_theme_payment_purchase', [
                'token' => $order->getToken()
            ])));
        }
    }
}
