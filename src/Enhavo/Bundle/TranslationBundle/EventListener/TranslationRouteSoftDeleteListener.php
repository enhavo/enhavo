<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TranslationRouteSoftDeleteListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenGeneratorInterface $tokenGenerator,
        private TranslationManager $translationManager,
        private readonly \Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface $routeTranslator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'enhavo_resource.pre_soft_delete' => 'preSoftDelete',
            'enhavo_resource.pre_undelete' => 'preUndelete',
        ];
    }

    public function preSoftDelete(ResourceEvent $event): void
    {
        $resource = $event->getSubject();
        if ($resource instanceof Routeable && $resource->getRoute() && is_subclass_of($resource, 'Enhavo\Bundle\RevisionBundle\Model\RevisionInterface')) {
            $locales = $this->translationManager->getLocales();
            $defaultLocale = $this->translationManager->getDefaultLocale();

            $translationRouteParameters = [];
            foreach ($locales as $locale) {
                if ($locale === $defaultLocale) {
                    continue;
                }
                $route = $this->routeTranslator->getTranslation($resource, 'route', $locale);
                if ($route) {
                    $translationRouteParameters[$locale] = [
                        'route_condition' => $route->getCondition(),
                        'route_static_prefix' => $route->getStaticPrefix(),
                    ];
                    $route->setCondition('false');
                    $route->setStaticPrefix(sprintf('/soft_deleted_%s', $this->tokenGenerator->generateToken()));
                    $this->routeTranslator->setTranslation($resource, 'route', $locale, $route);
                }
            }

            /** @var RevisionInterface&Routeable $resource */
            $parameters = $resource->getRevisionParameters();
            $parameters['translation_route_parameters'] = $translationRouteParameters;
            $resource->setRevisionParameters($parameters);
        }
    }

    public function preUndelete(ResourceEvent $event): void
    {
        $resource = $event->getSubject();
        if ($resource instanceof Routeable && $resource->getRoute() && is_subclass_of($resource, 'Enhavo\Bundle\RevisionBundle\Model\RevisionInterface')) {
            $locales = $this->translationManager->getLocales();
            $defaultLocale = $this->translationManager->getDefaultLocale();

            $parameters = $resource->getRevisionParameters();
            if (!isset($parameters['translation_route_parameters'])) {
                return;
            }
            $translationRouteParameters = $parameters['translation_route_parameters'];
            foreach ($locales as $locale) {
                if ($locale === $defaultLocale) {
                    continue;
                }
                if (!isset($translationRouteParameters[$locale])) {
                    continue;
                }
                $route = $this->routeTranslator->getTranslation($resource, 'route', $locale);
                if ($route) {
                    $route->setCondition($translationRouteParameters[$locale]['route_condition'] ?? '');
                    if (isset($translationRouteParameters[$locale]['route_static_prefix'])) {
                        $route->setStaticPrefix($translationRouteParameters[$locale]['route_static_prefix']);
                    }
                    $this->routeTranslator->setTranslation($resource, 'route', $locale, $route);
                }
            }
            unset($parameters['translation_route_parameters']);
            $resource->setRevisionParameters($parameters);
        }
    }
}
