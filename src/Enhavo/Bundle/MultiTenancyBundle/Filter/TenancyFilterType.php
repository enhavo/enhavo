<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Enhavo\Bundle\ResourceBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TenancyFilterType extends AbstractFilterType
{
    public function __construct(
        private readonly TenantManager $tenantManager
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $data['choices'] = $this->getTenantChoices($options);
    }

    protected function getInitialValue($options)
    {
        return $options['initial_value'] === null ? $this->tenantManager->getTenant()->getKey() : $options['initial_value'];
    }

    private function getTenantChoices($options): array
    {
        $data = [];
        $tenants = $this->tenantManager->getTenants();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach($tenants as $key => $tenant) {
            $data[] = [
                'label' => $propertyAccessor->getValue($tenant, $options['tenantLabelProperty']),
                'code' => $tenant->getKey(),
            ];
        }
        return $data;
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        $this->tenantManager->disableDoctrineFilter();

        if($value == null) {
            return;
        }

        $possibleValues = $this->getTenantChoices($options);
        $possibleValueFound = false;
        foreach ($possibleValues as $possibleValue) {
            if ($value == $possibleValue['code']) {
                $possibleValueFound = true;
                break;
            }
        }

        if (!$possibleValueFound) {
            throw new FilterException('Value submitted for TenancyFilter is not a valid tenant');
        }

        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'filter.tenancy.label',
            'translation_domain' => 'EnhavoMultiTenancyBundle',
            'tenantLabelProperty' => 'name',
            'component' => 'filter-option'
        ]);
    }

    public static function getName(): ?string
    {
        return 'tenancy';
    }
}
