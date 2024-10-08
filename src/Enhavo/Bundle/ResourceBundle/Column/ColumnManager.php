<?php

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ColumnManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
    )
    {
    }

    /**
     * @return Column[]
     */
    public function getColumns(array $configuration): array
    {
        $columns = [];
        foreach($configuration as $key => $options) {
            /** @var Column $column */
            $column = $this->factory->create($options, $key);

            if (!$column->isEnabled()) {
                continue;
            }

            if ($column->getPermission() !== null && !$this->checker->isGranted($column->getPermission())) {
                continue;
            }

            $columns[$key] = $column;
        }

        return $columns;
    }
}
