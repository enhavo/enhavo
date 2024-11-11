<?php

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
