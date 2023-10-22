<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

abstract class AbstractDataNormalizer implements DataNormalizerInterface
{
    protected function hasSerializationGroup(string|array $group, array $context): bool
    {
        if (empty($context['groups'])) {
            return false;
        }

        $groups = $this->getSerializationGroups($context);
        if (is_array($group)) {
            foreach ($group as $singeGroup) {
                if (in_array($singeGroup, $groups)) {
                    return true;
                }
            }
        } else if (is_string($group)) {
            return in_array($group, $groups);
        }

        return false;
    }

    private function getSerializationGroups(array $context): array
    {
        if (is_array($context['groups'])) {
           return $context['groups'];
        } else if (is_string($context['groups'])) {
            return [$context['groups']];
        }
        return [];
    }
}
