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

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Turns values and property names into human readable strings for a diff.
 */
class DiffValueFormatter
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function format(mixed $value): ?string
    {
        if (is_iterable($value)) {
            $labels = [];
            foreach ($value as $item) {
                $labels[] = $this->formatSingle($item);
            }
            $labels = array_filter($labels, fn (?string $item) => null !== $item);
            sort($labels);

            return $labels ? implode(', ', $labels) : null;
        }

        return $this->formatSingle($value);
    }

    /**
     * Label of a property: the configured label (translated) or the humanized property name.
     */
    public function getLabel(?string $label, ?string $translationDomain, ?string $property): ?string
    {
        if ('' === $label) {
            return '';
        }

        if (null !== $label) {
            return $this->translator->trans($label, [], $translationDomain);
        }

        return null === $property ? null : $this->humanize($property);
    }

    public function humanize(string $property): string
    {
        $words = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/[_-]+/'], ['$1 $2', ' '], $property));

        return ucfirst(trim($words));
    }

    private function formatSingle(mixed $value): ?string
    {
        return match (true) {
            null === $value => null,
            is_bool($value) => $this->translator->trans($value ? 'diff.value.yes' : 'diff.value.no', [], 'EnhavoResourceBundle'),
            $value instanceof \DateTimeInterface => $value->format('d.m.Y H:i'),
            is_array($value) => $value ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            is_string($value) => $this->normalizeText($value),
            is_scalar($value) => (string) $value,
            is_object($value) => $this->getObjectLabel($value),
            default => null,
        };
    }

    private function normalizeText(string $value): ?string
    {
        if ($value !== strip_tags($value)) {
            // keep words of adjacent block elements apart
            $value = html_entity_decode(strip_tags(preg_replace('/<[^>]+>/', ' $0', $value)), ENT_QUOTES | ENT_HTML5);
        }
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        return '' === $value ? null : $value;
    }

    private function getObjectLabel(object $object): string
    {
        foreach (['getTitle', 'getName', 'getLabel', 'getCode'] as $method) {
            if (method_exists($object, $method)) {
                $label = $object->$method();
                if (is_string($label) && '' !== $label) {
                    return $this->normalizeText($label) ?? $label;
                }
            }
        }

        if (method_exists($object, '__toString')) {
            return (string) $object;
        }

        $id = method_exists($object, 'getId') ? $object->getId() : null;

        return sprintf('%s #%s', (new \ReflectionClass($object))->getShortName(), $id ?? '?');
    }
}
