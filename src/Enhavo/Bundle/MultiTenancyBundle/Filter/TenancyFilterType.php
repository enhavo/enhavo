<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\Translation\TranslatorInterface;

class TenancyFilterType extends AbstractFilterType
{
    /**
     * @var TenantManager
     */
    private $tenantManager;

    public function __construct(TranslatorInterface $translator, TenantManager $tenantManager)
    {
        parent::__construct($translator);
        $this->tenantManager = $tenantManager;
    }

    public function createViewData($options, $name)
    {
        $data = parent::createViewData($options, $name);
        $data['choices'] = $this->getTenantChoices($options);
        return $data;
    }

    protected function getInitialValue($options)
    {
        return $options['initial_value'] === null ? $this->tenantManager->getTenant()->getKey() : $options['initial_value'];
    }

    protected function getTenantChoices($options)
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

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $this->tenantManager->disableDoctrineFilter();

        if($value == '') {
            return;
        }

        $possibleValues = $this->getTenantChoices($options);
        $possibleValueFound = false;
        foreach($possibleValues as $possibleValue) {
            if ($value == $possibleValue['code']) {
                $possibleValueFound = true;
                break;
            }
        }

        if(!$possibleValueFound) {
            throw new FilterException('Value submitted for TenancyFilter is not a valid tenant');
        }

        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'label' => 'filter.tenancy.label',
            'translation_domain' => 'EnhavoMultiTenancyBundle',
            'tenantLabelProperty' => 'name',
            'component' => 'filter-option'
        ]);
    }

    public function getType()
    {
        return 'tenancy';
    }
}
