<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\Type\BaseColumnType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Component\Type\AbstractType;

/**
 * @property DuplicateTypeInterface $parent
 */
abstract class AbstractDuplicateType extends AbstractType implements DuplicateTypeInterface
{
    public function duplicate($options, Value $newValue, Value $originalValue, $context): void
    {

    }

    public function finish($options, Value $newValue, Value $originalValue, $context): void
    {

    }
}
