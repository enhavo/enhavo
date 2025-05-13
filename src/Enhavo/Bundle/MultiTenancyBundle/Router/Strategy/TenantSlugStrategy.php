<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Router\Strategy;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TenantSlugStrategy extends AbstractStrategy
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param array $parameters
     * @param int   $referenceType
     * @param array $options
     *
     * @throws UrlResolverException
     * @throws PropertyNotExistsException
     *
     * @return string
     */
    public function generate($resource, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $slug = $this->getProperty($resource, $options['property']);
        $parameters = array_merge($parameters, ['slug' => $slug]);

        return $this->getRouter()->generate($this->getRoute($resource, $options), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'tenancy_aware_slug';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'property' => 'slug',
        ]);
        $optionsResolver->setRequired([
            'route',
        ]);
    }

    /**
     * @param array $options
     *
     * @throws UrlResolverException
     *
     * @return string
     */
    protected function getRoute($resource, $options)
    {
        $tenant = $this->resolver->getTenant()->getKey();

        if (is_array($options['route'])) {
            if (!isset($options['route'][$tenant])) {
                throw new UrlResolverException('Configuration error on TenantSlugStrategy for entity type "'.get_class($resource).'": No route configured for tenant "'.$tenant.'"');
            }

            return $options['route'][$tenant];
        }
        if (is_string($options['route'])) {
            return $options['route'];
        }
        throw new UrlResolverException('Configuration error on TenantSlugStrategy for entity type "'.get_class($resource).'": Option "route" needs to be string or array.');
    }
}
