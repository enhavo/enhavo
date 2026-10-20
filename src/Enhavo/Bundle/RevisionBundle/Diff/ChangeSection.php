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
 * The block a change belongs to.
 */
final readonly class ChangeSection
{
    public function __construct(
        public int $position,
        public string $label,
        public string $status,
    ) {
    }
}
