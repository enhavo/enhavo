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

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    use ContainerAwareTrait;

    /**
     * @var LocaleResolverInterface
     */
    private $resolver;

    /**
     * LocaleSubscriber constructor.
     */
    public function __construct(LocaleResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $locale = $this->resolver->resolve();
        $request = $event->getRequest();
        $request->attributes->set('_locale', $locale);
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
