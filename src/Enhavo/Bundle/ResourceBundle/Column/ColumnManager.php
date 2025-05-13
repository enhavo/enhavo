<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ColumnManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
    ) {
    }

    /**
     * @return Column[]
     */
    public function getColumns(array $configuration): array
    {
        $columns = [];
        foreach ($configuration as $key => $options) {
            /** @var Column $column */
            $column = $this->factory->create($options, $key);

            if (!$column->isEnabled()) {
                continue;
            }

            if (null !== $column->getPermission() && !$this->checker->isGranted($column->getPermission())) {
                continue;
            }

            $columns[$key] = $column;
        }

        return $columns;
    }
}
