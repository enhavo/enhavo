<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface FilterTypeInterface extends TypeInterface
{
    public function createViewData($options, Data $data): void;

    public function buildQuery($options, FilterQuery $query, mixed $value): void;

    public function getPermission($options): mixed;

    public function isEnabled($options): bool;
}
