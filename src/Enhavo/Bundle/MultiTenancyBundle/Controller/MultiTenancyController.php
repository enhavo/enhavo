<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Controller;

use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MultiTenancyController extends AbstractController
{
    /** @var TenantManager */
    protected $tenantManager;

    /** @var string */
    protected $sessionKey;

    public function __construct(TenantManager $tenantManager, $sessionKey)
    {
        $this->tenantManager = $tenantManager;
        $this->sessionKey = $sessionKey;
    }

    public function switchTenantAction(Request $request)
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

        return $this->redirect($redirect);
    }
}
