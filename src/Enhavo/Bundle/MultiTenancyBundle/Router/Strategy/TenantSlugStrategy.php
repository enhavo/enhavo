<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Router\Strategy;

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
     * @param $resource
     * @param array $parameters
     * @param int $referenceType
     * @param array $options
     * @return string
     * @throws UrlResolverException
     * @throws \Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException
     */
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
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
            'property' => 'slug'
        ]);
        $optionsResolver->setRequired([
            'route'
        ]);
    }

    /**
     * @param $resource
     * @param array $options
     * @return string
     * @throws UrlResolverException
     */
    protected function getRoute($resource, $options)
    {
        $tenant = $this->resolver->getTenant()->getKey();

        if (is_array($options['route'])) {
            if (!isset($options['route'][$tenant])) {
                throw new UrlResolverException('Configuration error on TenantSlugStrategy for entity type "' . get_class($resource) . '": No route configured for tenant "' . $tenant . '"');
            }
            return $options['route'][$tenant];
        }
        if (is_string($options['route'])) {
            return $options['route'];
        }
        throw new UrlResolverException('Configuration error on TenantSlugStrategy for entity type "' . get_class($resource) . '": Option "route" needs to be string or array.');
    }

}
