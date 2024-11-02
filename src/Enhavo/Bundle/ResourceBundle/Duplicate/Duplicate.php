<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-03-28
 * Time: 15:31
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property DuplicateTypeInterface $type
 * @property DuplicateTypeInterface[] $parents
 */
class Duplicate extends AbstractContainerType
{
    function duplicate($value, array $context = []): mixed
    {
        $newValue = new Value();

        $originalValue = new Value();
        $originalValue->setValue($value);

        foreach ($this->parents as $parent) {
            $parent->duplicate($this->options, $newValue, $originalValue, $context);
        }

        $this->type->duplicate($this->options, $newValue, $originalValue, $context);

        return $newValue->getValue();
    }
}
