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

final readonly class SnapshotValue
{
    /**
     * @param string|null $display human readable value
     * @param string|null $compare value used for the comparison, e.g. the checksum of a file whose copy has another id
     */
    public function __construct(
        public string $label,
        public ?string $display,
        public ?string $compare,
    ) {
    }
}
