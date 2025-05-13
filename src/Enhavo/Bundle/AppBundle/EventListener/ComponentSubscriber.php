<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\UX\TwigComponent\Event\PreRenderEvent;

class ComponentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TemplateResolver $templateResolver,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            PreRenderEvent::class => ['onPreRender'],
        ];
    }

    public function onPreRender(PreRenderEvent $event)
    {
        $event->setTemplate($this->templateResolver->resolve($event->getTemplate()));
    }
}
