<?php

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    public function __construct(EntityManagerInterface $em, LocaleResolver $localeResolver)
    {
        $this->em = $em;
        $this->localeResolver = $localeResolver;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        $translationRoute = $this->em->getRepository('EnhavoTranslationBundle:TranslationRoute')->findOneBy([
            'path' => $path
        ]);

        if($translationRoute) {
            $this->localeResolver->setLocale($translationRoute->getLocale());
        }

        return;
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 34)),
        );
    }
}