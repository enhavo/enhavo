<?php

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FilterManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $factory
    )
    {
    }

    public function createFiltersViewData(array $configuration)
    {
        $filters = [];
        foreach($configuration as $key => $options) {
            /** @var Filter $filter */
            $filter = $this->factory->create($options, $key);

            if($filter->isEnabled()) {
                continue;
            }

            if($filter->getPermission() !== null && !$this->checker->isGranted($filter->getPermission())) {
                continue;
            }

            $filters[] = $filter->createViewData();
        }

        return $filters;
    }

    public function getFilters(array $configuration): array
    {
        return [];
    }
}
