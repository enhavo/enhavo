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

use Enhavo\Component\Type\AbstractTypeExtension;

abstract class AbstractDuplicateTypeExtension extends AbstractTypeExtension implements DuplicateTypeExtensionInterface
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
    }

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
    }
}
