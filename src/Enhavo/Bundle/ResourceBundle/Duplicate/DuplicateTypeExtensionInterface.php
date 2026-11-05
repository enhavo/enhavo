<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Component\Type\TypeExtensionInterface;

interface DuplicateTypeExtensionInterface extends TypeExtensionInterface
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void;

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void;

    /**
     * Adds further differences to $diff, called after the extended type compared the property.
     */
    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void;
}
