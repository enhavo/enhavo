<?php

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FilterManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $factory,
    )
    {
    }

    public function getFilters(array $configuration): array
    {
        $filters = [];
        foreach ($configuration as $key => $options) {
            /** @var Filter $filter */
            $filter = $this->factory->create($options, $key);

            if (!$filter->isEnabled()) {
                continue;
            }

            if ($filter->getPermission() !== null && !$this->checker->isGranted($filter->getPermission())) {
                continue;
            }

            $filters[$key] = $filter;
        }

        return $filters;
    }
}
