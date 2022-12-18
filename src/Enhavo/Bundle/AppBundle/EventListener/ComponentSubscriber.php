<?php

namespace Enhavo\Bundle\AppBundle\EventListener;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use  Symfony\UX\TwigComponent\Event\PreRenderEvent;

class ComponentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TemplateManager $templateManager
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            PreRenderEvent::class => ['onPreRender'],
        ];
    }

    public function onPreRender(PreRenderEvent $event)
    {
        $event->setTemplate($this->templateManager->getTemplate($event->getTemplate()));
    }
}
