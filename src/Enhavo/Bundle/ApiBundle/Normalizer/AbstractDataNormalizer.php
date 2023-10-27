<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractDataNormalizer implements DataNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    protected bool $stopped = false;

    protected function hasSerializationGroup(string|array $group, array $context): bool
    {
        if (empty($context['groups'])) {
            return false;
        }

        $groups = $this->getSerializationGroupsFromContext($context);
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

    private function getSerializationGroupsFromContext(array $context): array
    {
        if (is_array($context['groups'])) {
           return $context['groups'];
        } else if (is_string($context['groups'])) {
            return [$context['groups']];
        }
        return [];
    }

    public function getSerializationGroups(array $groups, array $context = []): array
    {
        return $groups;
    }

    public function isStopped(): bool
    {
        return $this->stopped;
    }

    protected function stop(): void
    {
        $this->stopped = true;
    }
}
