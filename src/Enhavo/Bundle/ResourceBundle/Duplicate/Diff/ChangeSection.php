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

class ChangeSection
{
    public const STATUS_CHANGED = 'changed';
    public const STATUS_ADDED = 'added';
    public const STATUS_REMOVED = 'removed';

    /**
     * @param int $position 1-based, in the newer state where possible
     */
    public function __construct(
        private readonly int $position,
        private readonly string $label,
        private readonly string $status,
    ) {
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
