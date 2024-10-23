<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SwitchTenantEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly TenantManager $tenantManager,
        private readonly string $sessionKey,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $tenantKey = $request->get('tenant');
        $redirect = $request->get('redirect');

        $tenant = $this->tenantManager->getTenant($tenantKey);
        if (!$tenant) {
            throw new \Exception('Tenant key "' . $tenantKey . '" not found');
        }
        if ($tenant->getRole()) {
            if (!$this->isGranted($tenant->getRole())) {
                throw new AccessDeniedException();
            }
        }

        $request->getSession()->set($this->sessionKey, $tenant->getKey());

        $redirectResponse = new RedirectResponse($redirect);
        $context->setResponse($redirectResponse);
    }
}
