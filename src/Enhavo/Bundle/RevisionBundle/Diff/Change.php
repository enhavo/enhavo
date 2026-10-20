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

final readonly class Change
{
    /**
     * @param ChangeSection|null $section null for fields of the resource itself
     * @param string|null        $field   null for an added or removed block without filled fields
     */
    public function __construct(
        public ?ChangeSection $section,
        public ?string $field,
        public ?string $old,
        public ?string $new,
    ) {
    }
}
