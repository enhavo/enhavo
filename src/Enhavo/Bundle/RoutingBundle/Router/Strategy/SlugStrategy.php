<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;

use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SlugStrategy extends AbstractStrategy
{
    public function __construct(
        private RouterInterface $router,
    )
    {
    }

    public function generate($resource, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $slug = $this->getProperty($resource, $options['property']);
        $parameters = array_merge($parameters, ['slug' => $slug]);

        return $this->router->generate($options['route'], $parameters, $referenceType);
    }

    public function getType()
    {
        return 'slug';
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'property' => 'slug',
        ]);
        $optionsResolver->setRequired([
            'route',
        ]);
    }
}
