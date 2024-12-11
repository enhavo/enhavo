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
    function duplicate($source, $target = null, array $context = []): mixed
    {
        $sourceValue = new SourceValue($source);
        $targetValue = new TargetValue($target);

        foreach ($this->parents as $parent) {
            $parent->duplicate($this->options, $sourceValue, $targetValue, $context);
        }

        $this->type->duplicate($this->options, $sourceValue, $targetValue, $context);

        foreach ($this->parents as $parent) {
            $parent->finish($this->options, $sourceValue, $targetValue, $context);
        }

        $this->type->finish($this->options, $sourceValue, $targetValue, $context);

        return $targetValue->getValue();
    }
}
