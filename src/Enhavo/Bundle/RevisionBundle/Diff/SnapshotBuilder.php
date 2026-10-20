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

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Metadata\MetadataRepository;

/**
 * Reads the state of a resource for a comparison. Only properties that are copied into a revision
 * (duplicate group "revision") are read, so the comparison covers exactly what a revision stores.
 * Translated properties are read in every locale, their translations are stored outside the entity.
 */
class SnapshotBuilder
{
    private const GROUP = 'revision';
    private const MAX_DEPTH = 10;
    // order is already expressed by the index of items and blocks
    private const SKIPPED_PROPERTIES = ['position', 'slug'];

    public function __construct(
        private readonly MetadataRepository $duplicateMetadata,
        private readonly EntityManagerInterface $em,
        private readonly TranslationManager $translationManager,
    ) {
    }

    public function build(object $resource): Snapshot
    {
        $fields = [];
        $blocks = new \ArrayObject();
        $this->collect($resource, '', '', $fields, $blocks, 0);

        return new Snapshot($fields, $blocks->getArrayCopy());
    }

    /**
     * @param array<string, SnapshotValue>          $fields
     * @param \ArrayObject<int, BlockSnapshot>|null $blocks receives the blocks of a content node, null to flatten nested content into fields
     */
    private function collect(object $object, string $path, string $label, array &$fields, ?\ArrayObject $blocks, int $depth): void
    {
        if ($depth > self::MAX_DEPTH) {
            return;
        }

        $this->initialize($object);

        foreach ($this->getRevisionProperties($object) as $property => [$class, $type]) {
            $value = (new \ReflectionProperty($class, $property))->getValue($object);
            $propertyPath = ltrim($path.'.'.$property, '.');
            $propertyLabel = $this->join($label, $this->humanize($property));

            if ($value instanceof NodeInterface) {
                if (null !== $blocks) {
                    $this->collectBlocks($value, $blocks, $depth + 1);
                } else {
                    $this->collectNode($value, $propertyPath, $propertyLabel, $fields, $depth + 1);
                }
                continue;
            }

            if ('model' === $type && is_object($value) && !$value instanceof FileInterface) {
                $this->collect($value, $propertyPath, $propertyLabel, $fields, null, $depth + 1);
                continue;
            }

            if ('collection' === $type && is_iterable($value)) {
                $index = 0;
                foreach ($value as $item) {
                    ++$index;
                    if (is_object($item)) {
                        $this->collect($item, sprintf('%s[%d]', $propertyPath, $index), sprintf('%s %d', $propertyLabel, $index), $fields, null, $depth + 1);
                    }
                }
                continue;
            }

            $fields[$propertyPath] = $this->createValue($propertyLabel, $value);
            foreach ($this->getTranslations($object, $property) as $locale => $translation) {
                $fields[sprintf('%s@%s', $propertyPath, $locale)] = $this->createValue(sprintf('%s (%s)', $propertyLabel, $locale), $translation);
            }
        }
    }

    /**
     * @return array<string, mixed> locale => translated value, without the default locale (stored in the property itself)
     */
    private function getTranslations(object $object, string $property): array
    {
        if (!$this->translationManager->isTranslatable($object, $property)) {
            return [];
        }

        $translations = [];
        foreach ($this->translationManager->getLocales() as $locale) {
            if ($locale === $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $value = $this->translationManager->getProperty($object, $property, $locale);
            // revisions have no routes, translated routes would always show up as removed
            if ($value instanceof RouteInterface) {
                continue;
            }
            $translations[$locale] = $value;
        }

        return $translations;
    }

    /**
     * @param \ArrayObject<int, BlockSnapshot> $blocks
     */
    private function collectBlocks(NodeInterface $node, \ArrayObject $blocks, int $depth): void
    {
        foreach ($this->getChildren($node) as $child) {
            if (NodeInterface::TYPE_BLOCK === $child->getType() && null !== $child->getBlock()) {
                $fields = [
                    'enabled' => $this->createValue($this->humanize('enabled'), $child->isEnable() ?? true),
                ];
                $this->collect($child->getBlock(), '', '', $fields, null, $depth);
                $blocks[] = new BlockSnapshot((string) $child->getName(), $this->humanize((string) $child->getName()), $fields);
            } else {
                $this->collectBlocks($child, $blocks, $depth + 1);
            }
        }
    }

    /**
     * Content nested in a block is compared by position.
     *
     * @param array<string, SnapshotValue> $fields
     */
    private function collectNode(NodeInterface $node, string $path, string $label, array &$fields, int $depth): void
    {
        $index = 0;
        foreach ($this->getChildren($node) as $child) {
            ++$index;
            $childPath = sprintf('%s[%d]', $path, $index);
            $childLabel = sprintf('%s › %d. %s', $label, $index, $this->humanize((string) $child->getName()));
            if (NodeInterface::TYPE_BLOCK === $child->getType() && null !== $child->getBlock()) {
                $this->collect($child->getBlock(), $childPath, $childLabel, $fields, null, $depth);
            } else {
                $this->collectNode($child, $childPath, $childLabel, $fields, $depth + 1);
            }
        }
    }

    /**
     * @return NodeInterface[]
     */
    private function getChildren(NodeInterface $node): array
    {
        $children = [];
        foreach ($node->getChildren() ?? [] as $child) {
            $children[] = $child;
        }
        usort($children, fn (NodeInterface $a, NodeInterface $b) => ($a->getPosition() ?? 0) <=> ($b->getPosition() ?? 0));

        return $children;
    }

    /**
     * @return array<string, array{class-string, string}> property name => [declaring class, duplicate type]
     */
    private function getRevisionProperties(object $object): array
    {
        $class = $this->getRealClass($object);
        $properties = [];
        foreach (array_merge([$class], array_values(class_parents($class))) as $currentClass) {
            /** @var Metadata|null $metadata */
            $metadata = $this->duplicateMetadata->getMetadata($currentClass);
            foreach ($metadata?->getProperties() ?? [] as $property => $configs) {
                if (isset($properties[$property]) || in_array($property, self::SKIPPED_PROPERTIES, true) || !property_exists($currentClass, $property)) {
                    continue;
                }
                foreach ($configs as $config) {
                    if (in_array(self::GROUP, (array) ($config['groups'] ?? []), true)) {
                        $properties[$property] = [$currentClass, (string) ($config['type'] ?? '')];
                        break;
                    }
                }
            }
        }

        return $properties;
    }

    private function createValue(string $label, mixed $value): SnapshotValue
    {
        if ($value instanceof FileInterface) {
            $display = $value->getFilename();
            try {
                $compare = $value->getChecksum();
            } catch (\Throwable) {
                $compare = $display;
            }

            return new SnapshotValue($label, $display, $compare);
        }

        if (is_iterable($value)) {
            $labels = [];
            foreach ($value as $item) {
                $labels[] = $this->format($item);
            }
            $labels = array_filter($labels, fn (?string $item) => null !== $item);
            sort($labels);
            $display = $labels ? implode(', ', $labels) : null;

            return new SnapshotValue($label, $display, $display);
        }

        $display = $this->format($value);

        return new SnapshotValue($label, $display, $display);
    }

    private function format(mixed $value): ?string
    {
        return match (true) {
            null === $value => null,
            is_bool($value) => $value ? 'Yes' : 'No',
            $value instanceof \DateTimeInterface => $value->format('d.m.Y H:i'),
            is_array($value) => $value ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            is_string($value) => $this->normalizeText($value),
            is_scalar($value) => (string) $value,
            $value instanceof FileInterface => $value->getFilename(),
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
        $this->initialize($object);
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

        return sprintf('%s #%s', (new \ReflectionClass($this->getRealClass($object)))->getShortName(), $id ?? '?');
    }

    private function initialize(object $object): void
    {
        try {
            $this->em->initializeObject($object);
        } catch (\Throwable) {
            // not a doctrine object, nothing to load
        }
    }

    private function getRealClass(object $object): string
    {
        try {
            return $this->em->getClassMetadata(get_class($object))->getName();
        } catch (\Throwable) {
            return get_class($object);
        }
    }

    private function humanize(string $property): string
    {
        $words = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/[_-]+/'], ['$1 $2', ' '], $property));

        return ucfirst(trim($words));
    }

    private function join(string $prefix, string $label): string
    {
        return '' === $prefix ? $label : $prefix.' › '.$label;
    }
}
