<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Router;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Enhavo\Bundle\TranslationBundle\EventListener\AccessControl;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TranslationRouteStrategy extends AbstractStrategy
{
    public function __construct(
        private RouterInterface $router,
        private TranslationManager $translationManager,
        private AccessControl $accessControl,
        private LocaleResolverInterface $localeResolver,
    )
    {
    }

    public function generate($resource, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = []): string
    {
        if (!$this->accessControl->isAccess()) {
            return $this->router->generate($resource->getRoute()->getName(), $parameters, $referenceType);
        }

        $locale = $this->localeResolver->resolve();

        $type = $this->translationManager->getTranslation($resource, 'route');
        $value = $type->getTranslation($resource, 'route', $locale);

        if ($value !== null) {
            return $this->router->generate($value->getName(), $parameters, $referenceType);
        }

        return $this->router->generate($resource->getRoute()->getName(), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'translation_route';
    }
}
