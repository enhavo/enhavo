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
 * Comparable state of a resource: flat fields plus the blocks of its content in order.
 */
final readonly class Snapshot
{
    /**
     * @param array<string, SnapshotValue> $fields keyed by property path
     * @param BlockSnapshot[]              $blocks
     */
    public function __construct(
        public array $fields,
        public array $blocks,
    ) {
    }
}
