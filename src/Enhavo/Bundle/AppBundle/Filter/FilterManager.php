<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-27
 * Time: 12:47
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FilterManager
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var FilterFactory
     */
    private $factory;

    public function __construct(AuthorizationCheckerInterface $checker, FilterFactory $factory)
    {
        $this->checker = $checker;
        $this->factory = $factory;
    }

    public function createFiltersViewData(array $configuration)
    {
        $filters = [];
        foreach($configuration as $name => $options) {
            /** @var Filter $filter */
            $filter = $this->factory->createFilter($name, $options);

            if($filter->isHidden()) {
                continue;
            }

            if($filter->getPermission() !== null && !$this->checker->isGranted($filter->getPermission())) {
                continue;
            }

            $filters[] = $filter;
        }

        return $filters;
    }
}