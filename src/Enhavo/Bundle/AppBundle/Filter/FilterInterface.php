<?php

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface FilterInterface extends TypeInterface
{
    public function render($options, $value);

    public function buildQuery(FilterQuery $query, $options, $value);
}