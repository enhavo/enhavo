<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Component\Type\AbstractType;

/**
 * @property DuplicateTypeInterface $parent
 */
abstract class AbstractDuplicateType extends AbstractType implements DuplicateTypeInterface
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {

    }

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {

    }

    protected function isGroupSelected($options, $context): bool
    {
        $group = $context['groups'] ?? null;

        if ($options['groups'] === null && $group === null) {
            return true;
        }

        if (isset($options['groups']) && $options['groups'] === true) {
            return true;
        }

        $groups = match (gettype($options['groups'])) {
            'array' => $options['groups'],
            'string' => [$options['groups']],
            default => [],
        };

        $targetGroups = match (gettype($group)) {
            'array' => $group,
            'string' => [$group],
            default => [],
        };

        foreach ($targetGroups as $targetGroup) {
            if (in_array($targetGroup, $groups)) {
                return true;
            }
        }

        return false;
    }
}
