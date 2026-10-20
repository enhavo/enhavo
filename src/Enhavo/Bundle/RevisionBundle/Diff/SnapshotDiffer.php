<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Diff;

/**
 * Compares two snapshots. Blocks are matched by their type in order (longest common subsequence),
 * so an inserted or removed block shows up as such instead of shifting all following blocks.
 */
class SnapshotDiffer
{
    public const STATUS_CHANGED = 'changed';
    public const STATUS_ADDED = 'added';
    public const STATUS_REMOVED = 'removed';

    /**
     * @return Change[] in display order: fields first, then blocks in order of the newer snapshot
     */
    public function diff(Snapshot $old, Snapshot $new): array
    {
        $changes = $this->diffFields($old->fields, $new->fields, null);

        foreach ($this->alignBlocks($old->blocks, $new->blocks) as [$oldBlock, $newBlock, $position]) {
            if (null !== $oldBlock && null !== $newBlock) {
                $section = new ChangeSection($position, $newBlock->label, self::STATUS_CHANGED);
                array_push($changes, ...$this->diffFields($oldBlock->fields, $newBlock->fields, $section));
            } elseif (null !== $newBlock) {
                $section = new ChangeSection($position, $newBlock->label, self::STATUS_ADDED);
                array_push($changes, ...$this->listBlock($newBlock, $section, false));
            } else {
                $section = new ChangeSection($position, $oldBlock->label, self::STATUS_REMOVED);
                array_push($changes, ...$this->listBlock($oldBlock, $section, true));
            }
        }

        return $changes;
    }

    /**
     * @param array<string, SnapshotValue> $old
     * @param array<string, SnapshotValue> $new
     *
     * @return Change[]
     */
    private function diffFields(array $old, array $new, ?ChangeSection $section): array
    {
        $changes = [];
        foreach (array_unique(array_merge(array_keys($old), array_keys($new))) as $key) {
            $oldValue = $old[$key] ?? null;
            $newValue = $new[$key] ?? null;
            if ($oldValue?->compare === $newValue?->compare) {
                continue;
            }
            $changes[] = new Change($section, ($newValue ?? $oldValue)->label, $oldValue?->display, $newValue?->display);
        }

        return $changes;
    }

    /**
     * Lists the filled fields of an added or removed block.
     *
     * @return Change[]
     */
    private function listBlock(BlockSnapshot $block, ChangeSection $section, bool $removed): array
    {
        $changes = [];
        foreach ($block->fields as $key => $value) {
            if (null === $value->display || 'enabled' === $key) {
                continue;
            }
            $changes[] = $removed
                ? new Change($section, $value->label, $value->display, null)
                : new Change($section, $value->label, null, $value->display);
        }

        if (!$changes) {
            // an empty block still has to show up
            $changes[] = new Change($section, null, null, null);
        }

        return $changes;
    }

    /**
     * @param BlockSnapshot[] $old
     * @param BlockSnapshot[] $new
     *
     * @return array<int, array{BlockSnapshot|null, BlockSnapshot|null, int}> [old, new, position (1-based, in the newer snapshot where possible)]
     */
    private function alignBlocks(array $old, array $new): array
    {
        $oldCount = count($old);
        $newCount = count($new);

        // lcs[i][j] = length of the common subsequence of old[i..] and new[j..]
        $lcs = array_fill(0, $oldCount + 1, array_fill(0, $newCount + 1, 0));
        for ($i = $oldCount - 1; $i >= 0; --$i) {
            for ($j = $newCount - 1; $j >= 0; --$j) {
                $lcs[$i][$j] = $old[$i]->type === $new[$j]->type
                    ? $lcs[$i + 1][$j + 1] + 1
                    : max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
            }
        }

        $result = [];
        $i = 0;
        $j = 0;
        while ($i < $oldCount || $j < $newCount) {
            if ($i < $oldCount && $j < $newCount && $old[$i]->type === $new[$j]->type) {
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
}
