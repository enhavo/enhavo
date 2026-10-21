<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Diff;

class Change
{
    /**
     * @param ChangeSection|null $section null for fields of the resource itself
     * @param string|null        $field   null for an added or removed section without filled fields
     */
    public function __construct(
        private readonly ?ChangeSection $section,
        private readonly ?string $field,
        private readonly ?string $old,
        private readonly ?string $new,
    ) {
    }

    public function getSection(): ?ChangeSection
    {
        return $this->section;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getOld(): ?string
    {
        return $this->old;
    }

    public function getNew(): ?string
    {
        return $this->new;
    }
}
