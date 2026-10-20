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

final readonly class BlockSnapshot
{
    /**
     * @param string                       $type   block type name of the node, e.g. "text"
     * @param array<string, SnapshotValue> $fields keyed by property path
     */
    public function __construct(
        public string $type,
        public string $label,
        public array $fields,
    ) {
    }
}
