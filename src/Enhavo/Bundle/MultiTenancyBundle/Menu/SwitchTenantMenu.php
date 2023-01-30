<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\DropdownMenu;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SwitchTenantMenu extends DropdownMenu
{
    /** @var TenantManager */
    protected $tenantManager;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * TenantMenu constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param TenantManager $tenantManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router, TenantManager $tenantManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($translator, $router);
        $this->tenantManager = $tenantManager;
        $this->authorizationChecker = $authorizationChecker;
    }


    protected function getChoices($options)
    {
        $result = [];
        $tenants = $this->getTenants();
        foreach($tenants as $tenant) {
            $result[$tenant->getKey()] = $tenant->getName();
        }

        return $result;
    }

    protected function getValue(array $options)
    {
        return $this->tenantManager->getTenant()->getKey();
    }

    /**
     * @return Tenant[]
     */
    protected function getTenants()
    {
        $result = [];

        $tenants = $this->tenantManager->getTenants();
        foreach ($tenants as $tenant) {
            if ($tenant->getRole()) {
                if ($this->authorizationChecker->isGranted($tenant->getRole())) {
                    $result []= $tenant;
                }
            } else {
                $result []= $tenant;
            }
        }

        return $result;
    }

    public function isHidden(array $options)
    {
        if ($this->getOption('hidden', $options, null)) {
            return true;
        }
        if(count($this->getTenants()) <= 1) {
            return true;
        }
        return false;
    }

    public function isActive(array $options)
    {
        return false;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'event' => 'tenantChange',
            'role' => 'ROLE_ADMIN',
            'component' => 'menu-switch-tenant',
        ]);
        $resolver->remove(['value', 'choices']);
    }

    public function getType()
    {
        return 'switch_tenant';
    }
}
