<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\DropdownMenuType;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SwitchTenantMenu extends AbstractMenuType
{
    public function __construct(
        private readonly TenantManager $tenantManager,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly RouterInterface $router,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data['url'] = $this->router->generate($options['route']);
    }

    private function getChoices(): array
    {
        $result = [];
        $tenants = $this->getTenants();
        foreach ($tenants as $tenant) {
            $result[$tenant->getKey()] = $tenant->getName();
        }

        return $result;
    }

    private function getValue(): ?string
    {
        return $this->tenantManager->getTenant()?->getKey();
    }

    /** @return Tenant[] */
    private function getTenants(): array
    {
        $result = [];

        $tenants = $this->tenantManager->getTenants();
        foreach ($tenants as $tenant) {
            if ($tenant->getRole()) {
                if ($this->authorizationChecker->isGranted($tenant->getRole())) {
                    $result[] = $tenant;
                }
            } else {
                $result[] = $tenant;
            }
        }

        return $result;
    }

    public function isEnabled(array $options): bool
    {
        if (!$options['enabled']) {
            return false;
        } elseif (count($this->getTenants()) <= 1) {
            return false;
        }

        return true;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => 'ROLE_ADMIN',
            'model' => 'SwitchTenantMenuItem',
            'choices' => $this->getChoices(),
            'value' => $this->getValue(),
            'route' => 'enhavo_multi_tenancy_admin_api_tenant_switch',
        ]);
    }

    public static function getParentType(): ?string
    {
        return DropdownMenuType::class;
    }

    public static function getName(): ?string
    {
        return 'switch_tenant';
    }
}
