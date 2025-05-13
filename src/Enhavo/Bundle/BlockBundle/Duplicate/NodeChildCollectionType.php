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

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class NodeChildCollectionType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    ) {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (null === $sourceValue->getValue()) {
            $targetValue->setValue(null);
        } else {
            $parent = $sourceValue->getParent();
            if (!$parent instanceof NodeInterface) {
                throw new \InvalidArgumentException(sprintf('Duplicate type %s is only valid on properties of an instance of %s', self::class, NodeInterface::class));
            }
            if (NodeInterface::TYPE_BLOCK === $parent->getType()) {
                // Stop on blocks. Iterating through the whole tree from root will create faulty block subtrees that
                // reference the original blocks instead of the copies. Blocks with children need to add their own
                // duplication attributes instead.
                return;
            }

            $propertyAccessor = new PropertyAccessor();
            $target = $targetValue->getParent();
            $values = [];
            foreach ($sourceValue->getValue() as $key => $item) {
                $values[] = $this->duplicateFactory->duplicate($item, null, $context);
            }
            $propertyAccessor->setValue($target, $targetValue->getPropertyName(), $values);
            $collection = $propertyAccessor->getValue($target, $targetValue->getPropertyName());
            $targetValue->setValue($collection);
        }
    }
}
