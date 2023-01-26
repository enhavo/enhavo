<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Manager\PromotionManager;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PromotionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PromotionManager $promotionManager
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceEvents::PRE_CREATE => 'onPreSave',
            ResourceEvents::PRE_UPDATE => 'onPreSave',
        ];
    }

    public function onPreSave(ResourceEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof PromotionInterface) {
            $this->promotionManager->update($subject);
        }
    }
}
