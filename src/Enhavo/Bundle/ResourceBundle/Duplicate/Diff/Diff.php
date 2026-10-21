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

use Enhavo\Bundle\ResourceBundle\Duplicate\DiffFactory;

/**
 * Collects the changes between two states of a resource. Duplicate types add changes to it in their diff method,
 * the current field label is built from the properties the diff is passing through.
 */
class Diff
{
    /** @var Change[] */
    private array $changes = [];

    /** @var string[] */
    private array $labels = [];

    private ?ChangeSection $section = null;

    private ?string $suffix = null;

    public function __construct(
        private readonly DiffFactory $diffFactory,
        private readonly DiffValueFormatter $formatter,
    ) {
    }

    /**
     * Compares two nested objects, e.g. the value of a model or an item of a collection.
     */
    public function diffObject(?object $old, ?object $new, array $context): void
    {
        $this->diffFactory->diffObject($this, $old, $new, $context);
    }

    public function enter(?string $label): void
    {
        $this->labels[] = $label;
    }

    public function leave(): void
    {
        array_pop($this->labels);
    }

    /**
     * Adds a change if the formatted values differ.
     */
    public function addValueChange(mixed $old, mixed $new, ?string $suffix = null): void
    {
        $oldDisplay = $this->formatter->format($old);
        $newDisplay = $this->formatter->format($new);

        if ($oldDisplay !== $newDisplay) {
            $this->addChange($oldDisplay, $newDisplay, $suffix);
        }
    }

    /**
     * Adds a change without comparing, the caller decides whether the values differ.
     */
    public function addChange(?string $old, ?string $new, ?string $suffix = null): void
    {
        $this->changes[] = new Change($this->section, $this->getLabel($suffix ?? $this->suffix), $old, $new);
    }

    /**
     * Runs $callback with a suffix for all labels, e.g. the locale of a translation.
     */
    public function withSuffix(string $suffix, callable $callback): void
    {
        $parentSuffix = $this->suffix;
        $this->suffix = $suffix;

        $callback();

        $this->suffix = $parentSuffix;
    }

    /**
     * Runs $callback inside a section (e.g. a block). Labels start fresh inside the section, and a section without
     * any change still shows up if it was added or removed.
     */
    public function inSection(ChangeSection $section, callable $callback): void
    {
        $labels = $this->labels;
        $parentSection = $this->section;
        $count = count($this->changes);

        $this->labels = [];
        $this->section = $section;

        $callback();

        if ($count === count($this->changes) && ChangeSection::STATUS_CHANGED !== $section->getStatus()) {
            $this->changes[] = new Change($section, null, null, null);
        }

        $this->labels = $labels;
        $this->section = $parentSection;
    }

    public function isInSection(): bool
    {
        return null !== $this->section;
    }

    public function getFormatter(): DiffValueFormatter
    {
        return $this->formatter;
    }

    /**
     * @return Change[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    private function getLabel(?string $suffix): ?string
    {
        $labels = array_filter($this->labels, fn (?string $label) => null !== $label && '' !== $label);
        if (0 === count($labels)) {
            return $suffix;
        }

        $label = implode(' › ', $labels);

        return null === $suffix ? $label : sprintf('%s (%s)', $label, $suffix);
    }
}
