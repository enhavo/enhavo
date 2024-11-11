<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\DropdownMenuType;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SwitchTenantMenu extends AbstractMenuType
{
    public function __construct(
        private readonly TenantManager $tenantManager,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data->set('choices' , $this->getChoices($options));
    }

    private function getChoices($options): array
    {
        $result = [];
        $tenants = $this->getTenants();
        foreach($tenants as $tenant) {
            $result[$tenant->getKey()] = $tenant->getName();
        }

        return $result;
    }

    private function getValue(array $options): string
    {
        return $this->tenantManager->getTenant()->getKey();
    }

    /**
     * @return Tenant[]
     */
    private function getTenants(): array
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

    public function isEnabled(array $options): bool
    {
        if (!$options['enabled']) {
            return false;
        } else if (count($this->getTenants()) <= 1) {
            return false;
        }

        return true;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'event' => 'tenantChange',
            'role' => 'ROLE_ADMIN',
            'component' => 'menu-switch-tenant',
        ]);
        $resolver->remove(['value', 'choices']);
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
