<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\UserBundle\Exception\TokenInvalidException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TokenInvalidSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TemplateResolver $templateResolver,
        private Environment $templateEngine,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onException', 1],
        ];
    }

    public function onException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof TokenInvalidException) {
            $content = $this->templateEngine->render($this->templateResolver->resolve('theme/error/token-invalid.html.twig'));
            $event->setResponse(new Response($content, 404));
        }
    }
}
