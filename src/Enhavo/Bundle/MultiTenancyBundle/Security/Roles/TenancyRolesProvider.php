<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Security\Roles;

use Enhavo\Bundle\AppBundle\Security\Roles\RolesProvider;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TenancyRolesProvider implements RolesProvider
{
    /**
     * @var array
     */
    private $roles = array();

    public function __construct(ProviderInterface $tenantProvider, TranslatorInterface $translator)
    {
        foreach($tenantProvider->getTenants() as $tenant) {
            $this->roles[$translator->trans('roles.label', ['%name%' => $tenant->getName()], 'EnhavoMultiTenancyBundle')] = $tenant->getRole();
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
