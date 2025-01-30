<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Column\Type;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Enhavo\Bundle\RoutingBundle\Column\Type\UrlColumnType;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

class TenantAwareUrlColumnType extends UrlColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $resolverType = $this->getOption('resolver_type', $options, 'default');

        /** @var Router $router */
        $router = $this->container->get('enhavo_routing.router');
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getMainRequest();
        /** @var TenantManager $tenantManager */
        $tenantManager = $this->container->get('enhavo_multi_tenancy.manager');

        $url = $router->generate($resource, [], UrlGenerator::ABSOLUTE_PATH, $resolverType);
        if ($resource instanceof TenantAwareInterface) {
            $domain = $tenantManager->getTenant($resource->getTenant())->getBaseUrl();
        } else {
            $domain = $request->getHost();
        }

        return [
            'component' => 'open-action',
            'target' => $options['target'],
            'icon' => $options['icon'],
            'url' => sprintf('%s://%s%s', $request->getScheme(), $domain, $url)
        ];
    }

    public function getType()
    {
        return 'tenant_aware_url';
    }
}
