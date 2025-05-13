<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Duplicate;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\ModelDuplicateType;

class BlockDuplicateType extends AbstractDuplicateType
{
    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $parent = $targetValue->getParent();
        $block = $targetValue->getValue();
        if ($block instanceof BlockInterface && $parent instanceof NodeInterface) {
            $block->setNode($parent);
        }
    }

    public static function getParentType(): ?string
    {
        return ModelDuplicateType::class;
    }
}
