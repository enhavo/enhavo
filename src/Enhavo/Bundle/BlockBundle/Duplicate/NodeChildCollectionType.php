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
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\ChangeSection;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

    /**
     * Blocks of a content are shown as sections, matched by their type in order (longest common subsequence), so an
     * inserted or removed block shows up as such instead of shifting all following blocks. Content nested in a block
     * (e.g. columns) is compared by position inside the section of that block.
     */
    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $oldNodes = $this->getBlockNodes($oldValue->getValue());
        $newNodes = $this->getBlockNodes($newValue->getValue());

        if ($diff->isInSection()) {
            $this->diffByPosition($oldNodes, $newNodes, $diff, $context);

            return;
        }

        foreach ($this->alignNodes($oldNodes, $newNodes) as [$oldNode, $newNode, $position]) {
            $node = $newNode ?? $oldNode;
            $status = match (true) {
                null === $oldNode => ChangeSection::STATUS_ADDED,
                null === $newNode => ChangeSection::STATUS_REMOVED,
                default => ChangeSection::STATUS_CHANGED,
            };
            $label = $diff->getFormatter()->humanize((string) $node->getName());

            $diff->inSection(new ChangeSection($position, $label, $status), function () use ($oldNode, $newNode, $diff, $context) {
                if ($oldNode && $newNode) {
                    $diff->enter($diff->getFormatter()->humanize('enabled'));
                    $diff->addValueChange($oldNode->isEnable() ?? true, $newNode->isEnable() ?? true);
                    $diff->leave();
                }
                $diff->diffObject($oldNode?->getBlock(), $newNode?->getBlock(), $context);
            });
        }
    }

    /**
     * @param NodeInterface[] $oldNodes
     * @param NodeInterface[] $newNodes
     */
    private function diffByPosition(array $oldNodes, array $newNodes, Diff $diff, array $context): void
    {
        $count = max(count($oldNodes), count($newNodes));
        for ($i = 0; $i < $count; ++$i) {
            $oldNode = $oldNodes[$i] ?? null;
            $newNode = $newNodes[$i] ?? null;
            $node = $newNode ?? $oldNode;

            $diff->enter(sprintf('%d. %s', $i + 1, $diff->getFormatter()->humanize((string) $node->getName())));
            if ($oldNode && $newNode && $oldNode->getName() !== $newNode->getName()) {
                // another block type at this position
                $diff->diffObject($oldNode->getBlock(), null, $context);
                $diff->diffObject(null, $newNode->getBlock(), $context);
            } else {
                $diff->diffObject($oldNode?->getBlock(), $newNode?->getBlock(), $context);
            }
            $diff->leave();
        }
    }

    /**
     * Block nodes in order, nodes without a block (e.g. lists) are flattened.
     *
     * @return NodeInterface[]
     */
    private function getBlockNodes(mixed $children): array
    {
        $nodes = [];
        foreach ($this->sort($children) as $child) {
            if (NodeInterface::TYPE_BLOCK === $child->getType() && null !== $child->getBlock()) {
                $nodes[] = $child;
            } else {
                array_push($nodes, ...$this->getBlockNodes($child->getChildren()));
            }
        }

        return $nodes;
    }

    /**
     * @return NodeInterface[]
     */
    private function sort(mixed $children): array
    {
        $nodes = [];
        foreach ($children ?? [] as $child) {
            if ($child instanceof NodeInterface) {
                $nodes[] = $child;
            }
        }
        usort($nodes, fn (NodeInterface $a, NodeInterface $b) => ($a->getPosition() ?? 0) <=> ($b->getPosition() ?? 0));

        return $nodes;
    }

    /**
     * @param NodeInterface[] $old
     * @param NodeInterface[] $new
     *
     * @return array<int, array{NodeInterface|null, NodeInterface|null, int}> [old, new, position (1-based, in the newer state where possible)]
     */
    private function alignNodes(array $old, array $new): array
    {
        $oldCount = count($old);
        $newCount = count($new);

        // lcs[i][j] = length of the common subsequence of old[i..] and new[j..]
        $lcs = array_fill(0, $oldCount + 1, array_fill(0, $newCount + 1, 0));
        for ($i = $oldCount - 1; $i >= 0; --$i) {
            for ($j = $newCount - 1; $j >= 0; --$j) {
                $lcs[$i][$j] = $old[$i]->getName() === $new[$j]->getName()
                    ? $lcs[$i + 1][$j + 1] + 1
                    : max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
            }
        }

        $result = [];
        $i = 0;
        $j = 0;
        while ($i < $oldCount || $j < $newCount) {
            if ($i < $oldCount && $j < $newCount && $old[$i]->getName() === $new[$j]->getName()) {
                $result[] = [$old[$i], $new[$j], $j + 1];
                ++$i;
                ++$j;
            } elseif ($j < $newCount && ($i >= $oldCount || $lcs[$i][$j + 1] >= $lcs[$i + 1][$j])) {
                $result[] = [null, $new[$j], $j + 1];
                ++$j;
            } else {
                $result[] = [$old[$i], null, $i + 1];
                ++$i;
            }
        }

        return $result;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // the children are no field of their own, their blocks are shown as sections or by position
            'label' => '',
        ]);
    }
}
