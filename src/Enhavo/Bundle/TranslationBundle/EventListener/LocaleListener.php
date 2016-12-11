<?php

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    public function __construct(LocaleResolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->localeResolver->resolveLocale();
        $request = $event->getRequest();
        $request->setLocale($this->localeResolver->getLocale());
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 33)),
        );
    }
}