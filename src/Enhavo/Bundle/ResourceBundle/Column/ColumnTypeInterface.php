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

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnTypeInterface extends TypeInterface
{
    public function buildSortingQuery($options, FilterQuery $query, string $direction): void;

    public function createColumnViewData(array $options, Data $data): void;

    public function createResourceViewData(array $options, object $resource, Data $data): void;

    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;

    public function configureOptions(OptionsResolver $resolver);
}
