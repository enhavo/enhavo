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
    function isApplicable(SourceValue $sourceValue, TargetValue $targetValue, array $context = []): bool
    {
        return $this->type->isApplicable($this->options, $sourceValue, $targetValue, $context);
    }

    function duplicate(SourceValue $sourceValue, TargetValue $targetValue, array $context = []): mixed
    {
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
