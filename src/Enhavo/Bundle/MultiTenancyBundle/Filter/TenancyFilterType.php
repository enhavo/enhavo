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
        $data['value'] = $this->tenantManager->getTenant()->getKey();
        return $data;
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

        $property = $options['property'];
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'label' => 'filter.tenancy.label',
            'translation_domain' => 'EnhavoMultiTenancyBundle',
            'hidden' => true,
            'tenantLabelProperty' => 'name',
            'component' => 'filter-option'
        ]);
    }

    public function getType()
    {
        return 'tenancy';
    }
}
