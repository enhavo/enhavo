<?php

namespace Enhavo\Bundle\RevisionBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use ReflectionClass;

class RevisionFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($this->isRevisionInterface($targetEntity)) {
            return '(' . $targetTableAlias . '.revisionState = "main" OR ' . $targetTableAlias . '.revisionState IS NULL)';
        }

        return '';
    }

    private function isRevisionInterface(ClassMetadata $targetEntity): bool
    {
        if ($targetEntity->reflClass->implementsInterface(RevisionInterface::class)) {
            return true;

        } else {
            /** @var ReflectionClass $subClass */
            foreach ($targetEntity->subClasses as $subClass) {
                $reflectionClass = new ReflectionClass($subClass);
                if ($reflectionClass->implementsInterface(RevisionInterface::class)) {
                    return true;
                }
            }
        }

        return false;
    }
}
