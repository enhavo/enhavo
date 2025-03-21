<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateTypeInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseDuplicateType extends AbstractType implements DuplicateTypeInterface
{
    public function isApplicable($options, SourceValue $sourceValue, TargetValue $targetValue, $context): bool
    {
        return $this->isGroupSelected($options, $context);
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {

    }

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null
        ]);
    }

    private function isGroupSelected($options, $context): bool
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
